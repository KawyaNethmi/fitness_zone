<?php 
include 'db_connection.php'; // Include the database connection file

// Initialize variables
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : "";
$message = "";
$trainerData = [
    'trainerID' => '',
    'name' => '',
    'specialization' => '',
    'phone' => '',
    'email' => '',
    'salary' => ''
];

// Redirect to admin_dashboard.php when exit button is clicked
if (isset($_POST['exit'])) {
    header("Location: admin_dashboard.php");
    exit();
}

// Handle Add or Update Trainer
if (isset($_POST['saveTrainer'])) {
    $trainerID = $_POST['trainerID'];
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $salary = $_POST['salary'];

    if ($trainerID) {
        // Update Trainer
        $sql = "UPDATE tbtrainers SET name = ?, specialization = ?, phone = ?, email = ?, salary = ? WHERE trainerID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssdi", $name, $specialization, $phone, $email, $salary, $trainerID);
    } else {
        // Add new Trainer
        $sql = "INSERT INTO tbtrainers (name, specialization, phone, email, salary) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssd", $name, $specialization, $phone, $email, $salary);
    }

    if ($stmt->execute()) {
        $message = $trainerID ? "Trainer updated successfully!" : "Trainer added successfully!";
        $trainerData = [
            'trainerID' => '',
            'name' => '',
            'specialization' => '',
            'phone' => '',
            'email' => '',
            'salary' => ''
        ];
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Search by Trainer ID
if (isset($_POST['searchTrainer']) && $searchTerm) {
    $sql = "SELECT * FROM tbtrainers WHERE trainerID LIKE ?";
    $searchParam = "%$searchTerm%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $trainerData = $row;
    } else {
        $message = "Trainer not found!";
    }
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM tbtrainers");
}

// Handle Delete Trainer
if (isset($_POST['deleteTrainer'])) {
    $trainerID = $_POST['trainerID'];
    $sql = "DELETE FROM tbtrainers WHERE trainerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $trainerID);
    if ($stmt->execute()) {
        $message = "Trainer deleted successfully!";
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
    <title>Manage Trainers</title>
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
            padding: 30px 0;
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

        .exit-button {
            background: #ff6a00;
            color: #fff;
            border: none;
            cursor: pointer;
            width: 40px; /* Fixed width to make it square */
            height: 40px; /* Fixed height to make it square */
            font-size: 1rem;
            border-radius: 5px; /* Optional: Rounded corners */
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute; /* Absolute positioning */
            top: 90%; /* Move the button down further */
            left: 50%; /* Horizontally center */
            transform: translate(-50%, -50%); /* Adjust for perfect centering */
        }

        /* Form Styling */
        .container {
            position: relative;
            max-width: 1200px;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px 60px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
        <h1>Manage Trainers</h1>
        <!-- Form for exit button -->
        <form method="post">
            <button type="submit" class="exit-button" name="exit">Exit</button>
        </form>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Search Form -->
        <div class="form-container">
            <h2>Search Trainer</h2>
            <form method="post">
                <input type="text" name="searchTerm" placeholder="Search by Trainer ID" value="<?php echo $searchTerm; ?>">
                <button type="submit" name="searchTrainer">Search</button>
            </form>
        </div>

        <!-- Add/Update Trainer Form -->
        <div class="form-container">
            <h2><?php echo $trainerData['trainerID'] ? 'Update Trainer' : 'Add Trainer'; ?></h2>
            <form method="post">
                <input type="hidden" name="trainerID" value="<?php echo $trainerData['trainerID']; ?>">
                <input type="text" name="name" placeholder="Name" value="<?php echo $trainerData['name']; ?>" required>
                <input type="text" name="specialization" placeholder="Specialization" value="<?php echo $trainerData['specialization']; ?>" required>
                <input type="text" name="phone" placeholder="Phone" value="<?php echo $trainerData['phone']; ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?php echo $trainerData['email']; ?>" required>
                <input type="number" step="0.01" name="salary" placeholder="Salary" value="<?php echo $trainerData['salary']; ?>" required>
                <button type="submit" name="saveTrainer"><?php echo $trainerData['trainerID'] ? 'Update Trainer' : 'Add Trainer'; ?></button>
            </form>
        </div>

        <!-- Trainers Table -->
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['trainerID']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['specialization']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['salary']; ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="searchTerm" value="<?php echo $row['trainerID']; ?>">
                                    <button type="submit" name="searchTrainer">Edit</button>
                                </form>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="trainerID" value="<?php echo $row['trainerID']; ?>">
                                    <button type="submit" name="deleteTrainer" onclick="return confirm('Are you sure you want to delete this trainer?');">Delete</button>
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
