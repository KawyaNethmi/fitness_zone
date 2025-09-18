<?php
session_start(); // Start the session

// Database connection
$servername = "localhost"; // Change this to your server name
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "fitness_zone"; // Change this to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to fetch the member data
    $sql = "SELECT * FROM members WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // Check if a member was found
    if ($result->num_rows > 0) {
        // Fetch the member data
        $member = $result->fetch_assoc();
        // Store member data in session variables
        $_SESSION['memberID'] = $member['memberID'];
        $_SESSION['username'] = $member['username'];
        // Redirect to the member dashboard
        header("Location: member_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login - Fitness Zone</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            background: linear-gradient(to right, #ff6a00, #ee0979); /* Orange to pink gradient */
        }

        /* Static Gradient Background */
        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* Dynamic Shape Animation */
        @keyframes shapeMove {
            0% {
                transform: translateX(0) translateY(0);
            }
            25% {
                transform: translateX(30%) translateY(30%);
            }
            50% {
                transform: translateX(-30%) translateY(-30%);
            }
            75% {
                transform: translateX(30%) translateY(-30%);
            }
            100% {
                transform: translateX(0) translateY(0);
            }
        }

        /* Moving Circles for Creativity */
        .background::before,
        .background::after {
            content: '';
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            animation: shapeMove 6s ease-in-out infinite;
        }

        .background::before {
            top: 25%;
            left: 15%;
            animation-delay: 0s;
        }

        .background::after {
            bottom: 15%;
            right: 15%;
            animation-delay: 3s;
        }

        /* Login Form Container */
        .login-container {
            background-color: rgba(255, 255, 255, 0.8); /* Light transparent background */
            padding: 50px 35px;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.1);
            width: 500px;
            max-width: 100%;
            animation: fadeIn 2s ease-out;
        }

        /* Keyframe for Fade-in Animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        /* Header Styling */
        .login-container h2 {
            text-align: center;
            font-size: 34px;
            color: #2e2e2e;
            margin-bottom: 20px;
            font-weight: bold;
        }

        /* Label Styling */
        label {
            font-size: 18px;
            margin-top: 15px;
            color: #333;
        }

        /* Input Field Styling */
        input {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            margin-top: 10px;
            width: 100%;
            transition: border 0.3s ease;
        }

        input:focus {
            border-color: #ee0979;
            outline: none;
        }

        /* Button Styling */
        button {
            padding: 15px;
            background-color: #ee0979; /* Pink color */
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        button:hover {
            background-color: #ff6a00; /* Orange color for hover */
        }

        button:active {
            background-color: #ee0979;
        }

        /* Error Message Styling */
        .error-message {
            color: #ff0000;
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* Register Link Styling */
        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #ee0979; /* Pink color */
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Creative Background -->
    <div class="background"></div>

    <div class="login-container">
        <h2>Member Login</h2>
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
