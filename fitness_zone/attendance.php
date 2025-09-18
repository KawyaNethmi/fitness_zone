<?php 
include 'db_connection.php'; // Include the database connection file

// Initialize variables
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : "";
$message = "";

// Handle Add Attendance
if (isset($_POST['addAttendance'])) {
    $memberID = $_POST['memberID'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO attendance (memberID, date, status) VALUES (?, ?, ?);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $memberID, $date, $status);

    if ($stmt->execute()) {
        $message = "Attendance added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Delete Attendance
if (isset($_POST['deleteAttendance'])) {
    $attendanceID = $_POST['attendanceID'];

    $sql = "DELETE FROM attendance WHERE attendanceID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attendanceID);

    if ($stmt->execute()) {
        $message = "Attendance deleted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch Attendance with Search
$sql = "SELECT a.attendanceID, a.memberID, a.date, a.status, m.username 
        FROM attendance a 
        JOIN members m ON a.memberID = m.memberID 
        WHERE a.attendanceID LIKE ?";
$searchParam = "%$searchTerm%";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attendance</title>
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
            flex-direction: column; /* Align header and container vertically */
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

        /* Header Styling */
        header {
            position: fixed; /* Fix the header to the top */
            top: 0;
            left: 0;
            width: 100%; /* Full width */
            background: rgba(255, 255, 255, 0.9); /* Light transparent background */
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Shadow for better separation */
            z-index: 10; /* Ensure it stays on top of other content */
            margin: 0; /* Reset any margins */
        }

        header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
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

        .invalid-message {
            color: #ff0000;
            text-align: center;
            font-size: 18px;
            margin-top: -10px;
        }

        /* Additional Styling */
        .container {
            position: relative;
            max-width: 1200px; /* Increased the max-width to make the container larger */
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px 60px; /* Increased padding for more space */
            border-radius: 15px; /* Slightly larger border radius for rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        }

        .form-container, .table-container {
            margin: 20px 0;
        }

        h1, h2 {
            text-align: center;
            color: #444;
        }

        form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        input, select, button {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
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
    </style>
</head>
<body>
    <div class="background"></div>

    <header>
        <h1>Manage Attendance</h1>
        <form method="post" action="admin_dashboard.php" style="display:inline;">
            <button type="submit" class="exit-button">Exit</button>
        </form>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Search Form -->
        <div class="form-container">
            <h2>Search Attendance</h2>
            <form method="post">
                <input type="text" name="searchTerm" placeholder="Search by Attendance ID" value="<?php echo $searchTerm; ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Add Attendance Form -->
        <div class="form-container">
            <h2>Add Attendance</h2>
            <form method="post">
                <select name="memberID" required>
                    <?php 
                        $membersQuery = "SELECT memberID, username FROM members";
                        $membersResult = $conn->query($membersQuery);
                        while ($member = $membersResult->fetch_assoc()) {
                            echo "<option value='{$member['memberID']}'>{$member['username']}</option>";
                        }
                    ?>
                </select>
                <input type="date" name="date" required>
                <select name="status" required>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
                <button type="submit" name="addAttendance">Add Attendance</button>
            </form>
        </div>

        <!-- Attendance Table -->
        <div class="table-container">
            <h2>Attendance List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Attendance ID</th>
                        <th>Member</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['attendanceID']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="attendanceID" value="<?php echo $row['attendanceID']; ?>">
                                    <button type="submit" name="deleteAttendance">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
