<?php
// Connecting to the database
$conn = new mysqli('localhost', 'root', '', 'fitness_zone');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Shifts
$shifts_result = $conn->query("SELECT s.shiftScheduleID, t.name AS trainerName, m.name AS maintainerName, s.time, s.shiftType
                               FROM shifts s
                               JOIN trainers t ON s.trainerID = t.trainerID
                               JOIN maintainers m ON s.maintainerID = m.maintainerID");

// Fetch Gym Schedule
$schedule_result = $conn->query("SELECT * FROM gymschedule");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Dashboard</title>
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

        /* Main Content Container */
        .container {
            position: relative;
            max-width: 1600px;  /* Increased max width for larger container */
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 60px 100px;  /* Increased padding for more spacious content */
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
    <h1>Trainer Dashboard</h1>
    <nav>
        <a href="vclasses.php">Classes</a>
        <a href="viewmembers.php">Members</a>
        <a href="home.php">Logout</a>

    </nav>
</header>

<div class="container">
    <!-- Shift Table -->
    <div class="table-container">
        <h2>Trainer Shifts</h2>
        <table>
            <thead>
                <tr>
                    <th>Shift ID</th>
                    <th>Trainer Name</th>
                    <th>Maintainer Name</th>
                    <th>Time</th>
                    <th>Shift Type</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($shift = $shifts_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $shift['shiftScheduleID'] ?></td>
                        <td><?= $shift['trainerName'] ?></td>
                        <td><?= $shift['maintainerName'] ?></td>
                        <td><?= $shift['time'] ?></td>
                        <td><?= $shift['shiftType'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Gym Schedule -->
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
</div>

<?php $conn->close(); ?>
</body>
</html>
