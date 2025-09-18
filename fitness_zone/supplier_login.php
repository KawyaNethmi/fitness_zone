<?php
session_start(); // Start the session

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables for error messages
$error = '';

// Database connection parameters
$servername = "localhost"; // Your database server
$db_username = "root"; // Default database username (usually 'root')
$db_password = ""; // Default database password (usually empty for local setups)
$dbname = "fitness_zone"; // Your database name

// Create a connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate input
    if (!empty($username) && !empty($password)) {
        // Prepare the SQL statement for checking credentials
        $stmt = $conn->prepare("SELECT password FROM suppliers WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($stored_password);
        $stmt->fetch();

        // Check the password directly (no hashing)
        if ($stored_password !== null && $stored_password === $password) {
            // Set session variables and redirect to the supplier dashboard
            $_SESSION['username'] = $username;
            header("Location: supplier_dashboard.php"); // Correct redirection to supplier dashboard
            exit();
        } else {
            $error = "Invalid username or password.";
        }

        // Close the statement
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Login</title>
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
            background: linear-gradient(to right, #ff7e5f, #feb47b); /* Soft peach gradient */
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
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2); /* Lighter, softer white */
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
            background-color: rgba(255, 255, 255, 0.85); /* Lighter transparent background */
            padding: 60px 40px; /* Increased padding for a spacious look */
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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

        /* Header Styling */
        .login-container h2 {
            text-align: center;
            font-size: 32px;
            color: #333;
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
            border: 1px solid #ff7e5f; /* Soft peach color */
            border-radius: 10px;
            font-size: 16px;
            margin-top: 10px;
            width: 100%;
            transition: border 0.3s ease;
        }

        input:focus {
            border-color: #feb47b; /* Lighter peach color */
            outline: none;
        }

        /* Button Styling */
        button {
            padding: 15px;
            background-color: #ff7e5f; /* Soft peach color */
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
            background-color: #feb47b; /* Lighter peach color */
        }

        button:active {
            background-color: #ff7e5f;
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
            color: #ff7e5f;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
       <!-- Dynamic Circles -->
       <div class="circle circle1"></div>
    <div class="circle circle2"></div>
    <div class="circle circle3"></div>
    <div class="circle circle4"></div>

    <!-- Creative Background -->
    <div class="background"></div>

    <div class="login-container">
        <h2>Supplier Login</h2>
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
    </div>
</body>
</html>
