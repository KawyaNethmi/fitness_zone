<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Fitness Zone</title>
    <link rel="stylesheet" href="css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f4f4f4;
        }

        header {
            background-color: #483D8B; /* Dark Slate Blue */
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 2em;
            font-weight: bold;
        }

        main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 30px 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .sidebar h2 {
            font-size: 1.5em;
            margin-top: 0;
            font-weight: 600;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            border-radius: 6px;
            transition: background 0.3s ease-in-out;
            margin-bottom: 12px;
            font-size: 1.1em;
        }

        .sidebar a:hover {
            background-color: #34495e;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 1.2em;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: #ecf0f1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .welcome-message {
            font-size: 2em;
            color: #483D8B;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            width: 100%;
            margin-top: 20px;
            justify-items: center;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            color: #34495e;
            margin-bottom: 15px;
            font-size: 1.5em;
            font-weight: 500;
        }

        .card p {
            font-size: 1.2em;
            color: #2c3e50;
        }

        .card i {
            font-size: 3em;
            color: #483D8B;
            margin-bottom: 15px;
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #483D8B;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
            font-size: 1em;
        }
    </style>
</head>
<body>
    <header>
        Admin Dashboard
    </header>
    <main>
        <div class="sidebar">
            <h2>Admin Control Center</h2>
            <a href="addmembers.php"><i class="fas fa-user-plus"></i> Members</a>
            <a href="attendance.php"><i class="fas fa-clipboard-check"></i> Track Attendance</a>
            <a href="trainers.php"><i class="fas fa-dumbbell"></i> Trainers</a>
            <a href="create_memberships.php"><i class="fas fa-id-card-alt"></i>Memberships</a>
            <a href="gymschedule.php"><i class="fas fa-calendar-alt"></i>  Gym Schedule</a>
            <a href="payment_history.php"><i class="fas fa-receipt"></i> Payment History</a>
            <a href="suppliers.php"><i class="fas fa-truck"></i> Suppliers</a>
            <a href="equipment.php"><i class="fas fa-tools"></i>  Equipment</a>
            <a href="maintainers.php"><i class="fas fa-users-cog"></i>  Maintainers</a>
            <a href="classes.php"><i class="fas fa-chalkboard-teacher"></i>  Classes</a>
            <a href="cleaners.php"><i class="fas fa-broom"></i>  Cleaners</a>
            <a href="shifts.php"><i class="fas fa-clock"></i>  Shifts</a>
            <a href="reports.php"><i class="fas fa-chart-line"></i>  Reports</a>
            <a href="expense_sheet.php"><i class="fas fa-money-check-alt"></i>  Expense Sheet</a>
            <a href="feedback.php"><i class="fas fa-comments"></i> Feedback</a>
            <a href="announcements.php"><i class="fas fa-bullhorn"></i>  Announcements</a>
            <a href="order.php"><i class="fas fa-box"></i> Orders</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="content">
            <div class="welcome-message">Welcome to the Admin Dashboard!</div>
            <div class="cards">
                <div class="card">
                    <i class="fas fa-users"></i>
                    <h3>Total Members</h3>
                    <p>150</p>
                </div>
                <div class="card">
                    <i class="fas fa-user-tie"></i>
                    <h3>Total Trainers</h3>
                    <p>10</p>
                </div>
                <div class="card">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>Total Classes</h3>
                    <p>5</p>
                </div>
                <div class="card">
                    <i class="fas fa-id-card"></i>
                    <h3>Active Memberships</h3>
                    <p>120</p>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Fitness Zone. All Rights Reserved.</p>
    </footer>
</body>
</html>
