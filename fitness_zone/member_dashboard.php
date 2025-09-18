<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
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

        /* Navigation Menu Styling */
        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        nav a {
            color: #333;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #ff6a00;
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

        /* Main Content Container */
        .container {
            position: relative;
            max-width: 1200px;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px 60px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .flex-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .flex-item {
            flex: 1;
            min-width: 400px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background: #f7f7f7;
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
    <h1>Member Dashboard</h1>
    <nav>
        <a href="mfeedback.php">Feedback</a>
        <a href="purchase_membership.php">Purchase Membership</a>
        <a href="vannouncements.php">Announcements</a>
        <a href="home.php">Logout</a>
    </nav>
</header>

<div class="container">
    <?php
    $conn = new mysqli('localhost', 'root', '', 'fitness_zone');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch Classes
    $classes_result = $conn->query("SELECT * FROM classes");
    ?>
    <div class="flex-container">
        <div class="flex-item">
            <div class="table-container">
                <h2>Classes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Class ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($class = $classes_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $class['classID'] ?></td>
                                <td><?= $class['name'] ?></td>
                                <td><?= $class['description'] ?></td>
                                <td><?= $class['status'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        // Fetch Trainers
        $trainers_result = $conn->query("SELECT * FROM trainers");
        ?>
        <div class="flex-item">
            <div class="table-container">
                <h2>Trainers List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Trainer ID</th>
                            <th>Name</th>
                            <th>Specialization</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($trainer = $trainers_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $trainer['trainerID'] ?></td>
                                <td><?= $trainer['name'] ?></td>
                                <td><?= $trainer['specialization'] ?></td>
                                <td><?= $trainer['phone'] ?></td>
                                <td><?= $trainer['email'] ?></td>
                                <td><?= $trainer['salary'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    // Fetch Gym Schedule
    $schedule_result = $conn->query("SELECT * FROM gymschedule");
    ?>
    <div class="table-container">
        <h2>Gym Schedule</h2>
        <table>
            <thead>
                <tr>
                    <th>Schedule ID</th>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Activity</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($schedule = $schedule_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $schedule['scheduleID'] ?></td>
                        <td><?= $schedule['day'] ?></td>
                        <td><?= $schedule['time'] ?></td>
                        <td><?= $schedule['activity'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php $conn->close(); ?>
</div>
</body>
</html>
