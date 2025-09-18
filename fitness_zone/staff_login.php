<?php
include 'db_connection.php'; // Include the database connection file

session_start(); // Start a session

$invalidMessage = ""; // Initialize the invalid message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM staff WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['username'] = $username;
        switch ($username) {
            case 'Admin':
                header("Location: admin_dashboard.php");
                break;
            case 'Trainer':
                header("Location: trainer_dashboard.php");
                break;
            case 'Expense Manager':
                header("Location: expense_manager_dashboard.php");
                break;
            case 'Equipment Manager':
                header("Location: equipment_manager_dashboard.php");
                break;
            case 'Cleaner':
                header("Location: cleaner_dashboard.php");
                break;
            case 'Maintainer':
                header("Location: maintainer_dashboard.php");
                break;
            default:
                echo "Invalid user role.";
        }
    } else {
        $invalidMessage = "Invalid username or password"; // Set the invalid message
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>

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
        }

        /* Static Gradient Background */
        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, #ff6a00, #ff1493); /* Static gradient */
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

        /* Add moving circles for extra creativity */
        .background::before,
        .background::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            animation: shapeMove 6s ease-in-out infinite;
        }

        .background::before {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .background::after {
            bottom: 10%;
            right: 20%;
            animation-delay: 3s;
        }

        /* Login Form Container */
        .login-container {
            background-color: rgba(255, 255, 255, 0.9); /* Light transparent background */
            padding: 60px 40px; /* Increased padding for a spacious look */
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 800px; /* Larger container */
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

        /* Login Form Styling */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .login-form h2 {
            text-align: center;
            font-size: 32px;
            color: #333;
        }

        .login-form input {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            transition: border 0.3s ease;
        }

        .login-form input:focus {
            border-color: #ff6a00;
            outline: none;
        }

        .login-form button {
            padding: 15px;
            background-color: #ff6a00;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .login-form button:hover {
            background-color: #ff1493;
        }

        .login-form button:active {
            background-color: #ff6a00;
        }

        /* Invalid Message Styling */
        .invalid-message {
            color: #ff0000;
            text-align: center;
            font-size: 18px;
            margin-top: -10px;
        }

        /* Link Styling */
        .link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            text-decoration: none;
            color: #ff6a00;
            transition: color 0.3s ease;
        }

        .link:hover {
            color: #ff1493;
        }
    </style>
</head>
<body>

    <!-- Creative Background -->
    <div class="background"></div>

    <div class="login-container">
        <form method="post" class="login-form">
            <h2>Staff Login</h2>
            <?php if (!empty($invalidMessage)): ?>
                <div class="invalid-message"> <?php echo $invalidMessage; ?> </div>
            <?php endif; ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

    </div>

</body>
</html>
