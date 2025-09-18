<?php 
include 'db_connection.php'; // Include the database connection file

// Initialize variables
$message = "";
$memberData = [
    'username' => '',
    'password' => '',
    'age' => '',
    'gender' => '',
    'phone' => '',
    'email' => '',
    'address' => ''
];

// Handle Registration
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $joinDate = date('Y-m-d'); // Current date for joining date

    // Insert Member Data into Database
    $sql = "INSERT INTO members (username, password, age, gender, phone, email, address, joinDate) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssss", $username, $password, $age, $gender, $phone, $email, $address, $joinDate);

    if ($stmt->execute()) {
        $message = "Registration successful!";
        $memberData = [
            'username' => '',
            'password' => '',
            'age' => '',
            'gender' => '',
            'phone' => '',
            'email' => '',
            'address' => ''
        ];
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Apply static gradient background */
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(45deg, #ff6a00, #ff1493); /* Static Gradient Background */
            flex-direction: column;
            padding-top: 80px; /* Add top padding to push content down below the fixed header */
            position: relative;
            overflow-x: hidden; /* Hide horizontal scrollbar */
            overflow-y: auto; /* Enable vertical scrolling */
        }

        /* Scrollbar customization */
        body::-webkit-scrollbar {
            width: 8px;
        }

        body::-webkit-scrollbar-thumb {
            background-color: #ff6a00;
            border-radius: 10px;
        }

        body::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Header Styling */
        header {
            position: fixed; 
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px 0;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }

        /* Exit Button */
        .exit-button {
            background: #ff6a00;
            color: #fff;
            border: none;
            cursor: pointer;
            width: 40px;
            height: 40px;
            font-size: 1rem;
            border-radius: 5px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 90%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Form Styling */
        .container {
            position: relative;
            width: 500px;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px 60px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-container, .table-container {
            margin: 20px 0;
        }

        h2 {
            text-align: center;
            color: #444;
        }

        form {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        input, select, textarea, button {
            padding: 15px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #ff6a00;
        }

        button {
            background: #ff6a00;
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
            padding: 15px;
        }

        button:hover {
            background: #ff1493;
        }

        .message {
            text-align: center;
            color: green;
            margin-top: 10px;
        }

        .error {
            text-align: center;
            color: red;
            margin-top: 10px;
        }

        /* Back to Login link styling */
        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #ff6a00;
            font-weight: bold;
            text-decoration: none;
        }

        .login-link a:hover {
            color: #ff1493;
        }

        /* Dynamic Shape Animation (circles floating in the background) */
        @keyframes moveCircles {
            0% {
                transform: translateX(-100%) translateY(-100%);
            }
            50% {
                transform: translateX(100%) translateY(100%);
            }
            100% {
                transform: translateX(-100%) translateY(-100%);
            }
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            animation: moveCircles 10s infinite linear;
            z-index: -1;
        }

        .circle1 {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 10%;
            animation-duration: 15s;
        }

        .circle2 {
            width: 120px;
            height: 120px;
            top: 50%;
            left: 50%;
            animation-duration: 18s;
        }

        .circle3 {
            width: 80px;
            height: 80px;
            top: 80%;
            left: 80%;
            animation-duration: 12s;
        }

        .circle4 {
            width: 150px;
            height: 150px;
            top: 30%;
            left: 70%;
            animation-duration: 10s;
        }
    </style>
</head>
<body>
<div class="circle circle1"></div>
    <div class="circle circle2"></div>
    <div class="circle circle3"></div>
    <div class="circle circle4"></div>

    <header>
        <h1>Register New Member</h1>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Registration Form -->
        <form method="post">
            <input type="text" name="username" placeholder="Username" value="<?php echo $memberData['username']; ?>" required>
            <input type="password" name="password" placeholder="Password" value="<?php echo $memberData['password']; ?>" required>
            <input type="number" name="age" placeholder="Age" value="<?php echo $memberData['age']; ?>" required>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male" <?php echo $memberData['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $memberData['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo $memberData['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
            <input type="text" name="phone" placeholder="Phone Number" value="<?php echo $memberData['phone']; ?>" required>
            <input type="email" name="email" placeholder="Email Address" value="<?php echo $memberData['email']; ?>" required>
            <textarea name="address" placeholder="Address" required><?php echo $memberData['address']; ?></textarea>
            <button type="submit" name="register">Register</button>
        </form>

        <!-- Back to Login Link -->
        <div class="login-link">
            <p>Already have an account? <a href="member_login.php">Back to Login</a></p>
        </div>
    </div>

</body>
</html>
