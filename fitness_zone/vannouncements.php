<?php
include 'db_connection.php'; // Include the database connection file

// Initialize variables
$message = '';

// Handle Search by Date
if (isset($_POST['searchByDate']) && isset($_POST['searchDate'])) {
    $searchDate = $_POST['searchDate'];
    $sql = "SELECT * FROM announcements WHERE date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchDate);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $message = "No announcements found for this date.";
    }
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM announcements ORDER BY date DESC");
}

// Handle Exit button
if (isset($_POST['exit'])) {
    header("Location: member_dashboard.php"); // Redirect to the admin dashboard
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
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
            align-items: flex-start;
            min-height: 100vh;
            background: linear-gradient(45deg, #ff6a00, #ff1493); /* Static Gradient Background */
            flex-direction: column;
            padding-top: 100px; /* Add extra top padding for the fixed header */
            position: relative;
            overflow-x: hidden; /* Hide horizontal scrollbar */
            overflow-y: auto; /* Enable vertical scrolling */
        }

        /* Moving Circle Animation for the Background */
        .circle {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            animation: moveCircle 10s linear infinite;
        }

        @keyframes moveCircle {
            0% {
                transform: translate(-50%, -50%) scale(1);
            }
            50% {
                transform: translate(50%, 50%) scale(1.5);
            }
            100% {
                transform: translate(-50%, -50%) scale(1);
            }
        }

        .circle:nth-child(1) {
            width: 200px;
            height: 200px;
            top: 10%;
            left: 10%;
        }

        .circle:nth-child(2) {
            width: 250px;
            height: 250px;
            top: 60%;
            left: 60%;
            animation-duration: 12s;
        }

        .circle:nth-child(3) {
            width: 300px;
            height: 300px;
            top: 30%;
            left: 80%;
            animation-duration: 8s;
        }

        /* Centered Container for content */
        .container {
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
            z-index: 1;
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

        /* Search Form Styling */
        .form-container {
            text-align: center;
            margin: 20px 0;
        }

        .form-container input, .form-container button {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-container input:focus {
            border-color: #ff6a00;
        }

        button {
            background: #ff6a00;
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #ff1493;
        }

        /* Message Bubbles Styling */
        .announcement-container {
            width: 100%;
            margin: 20px 0;
        }

        .message-bubble {
            background: rgba(255, 255, 255, 0.8);
            padding: 15px;
            border-radius: 20px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            font-size: 1rem;
        }

        .message-bubble .date {
            font-size: 0.85rem;
            color: #777;
            position: absolute;
            bottom: 5px;
            right: 10px;
        }
    </style>
</head>
<body>

    <!-- Moving Circles -->
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>

    <!-- Centered Container -->
    <div class="container">

        <!-- Header Section (Fixed) -->
        <header>
            <h1> Announcements</h1>
            <form method="post">
                <button type="submit" class="exit-button" name="exit">Exit</button>
            </form>
        </header>

        <!-- Search Form Section -->
        <div class="form-container">
            <h2>Search Announcements by Date</h2>
            <form method="post">
                <input type="date" name="searchDate" required>
                <button type="submit" name="searchByDate">Search</button>
            </form>
        </div>

        <!-- Announcement List Section -->
        <div class="announcement-container">
            <?php if ($message): ?>
                <p class="message"><?php echo $message; ?></p>
            <?php endif; ?>

            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="message-bubble">
                    <p><?php echo $row['message']; ?></p>
                    <span class="date"><?php echo $row['date']; ?></span>
                </div>
            <?php endwhile; ?>
        </div>

    </div>

</body>
</html>
