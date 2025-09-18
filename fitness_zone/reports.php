<?php
include 'db_connection.php'; // Include the database connection file

// Initialize variables
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : "";
$message = "";
$reportData = [
    'reportID' => '',
    'type' => '',
    'date' => '',
    'content' => ''
];

// Redirect to admin_dashboard.php when exit button is clicked
if (isset($_POST['exit'])) {
    header("Location: admin_dashboard.php");
    exit();
}

// Handle Add or Update Report
if (isset($_POST['saveReport'])) {
    $reportID = $_POST['reportID'];
    $type = $_POST['type'];
    $date = $_POST['date'];

    // Handle file upload
    if (isset($_FILES['contentFile']) && $_FILES['contentFile']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filePath = $uploadDir . basename($_FILES['contentFile']['name']);
        if (move_uploaded_file($_FILES['contentFile']['tmp_name'], $filePath)) {
            $content = $filePath;
        } else {
            $message = "Error uploading file.";
        }
    } else {
        $content = $_POST['content'];
    }

    if ($reportID) {
        // Update Report
        $sql = "UPDATE reports SET type = ?, date = ?, content = ? WHERE reportID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $type, $date, $content, $reportID);
    } else {
        // Add new Report
        $sql = "INSERT INTO reports (type, date, content) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $type, $date, $content);
    }

    if ($stmt->execute()) {
        $message = $reportID ? "Report updated successfully!" : "Report added successfully!";
        $reportData = [
            'reportID' => '',
            'type' => '',
            'date' => '',
            'content' => ''
        ];
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Search by Report ID
if (isset($_POST['searchReport']) && $searchTerm) {
    $sql = "SELECT * FROM reports WHERE reportID LIKE ?";
    $searchParam = "%$searchTerm%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $reportData = $row;
    } else {
        $message = "Report not found!";
    }
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM reports");
}

// Handle Delete Report
if (isset($_POST['deleteReport'])) {
    $reportID = $_POST['reportID'];
    $sql = "DELETE FROM reports WHERE reportID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reportID);
    if ($stmt->execute()) {
        $message = "Report deleted successfully!";
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
    <title>Manage Reports</title>
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
        <h1>Manage Reports</h1>
        <form method="post">
            <button type="submit" class="exit-button" name="exit">Exit</button>
        </form>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <div class="form-container">
            <h2>Search Report</h2>
            <form method="post">
                <input type="text" name="searchTerm" placeholder="Search by Report ID" value="<?php echo $searchTerm; ?>">
                <button type="submit" name="searchReport">Search</button>
            </form>
        </div>

        <div class="form-container">
            <h2><?php echo $reportData['reportID'] ? 'Update Report' : 'Add Report'; ?></h2>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="reportID" value="<?php echo $reportData['reportID']; ?>">
                <input type="text" name="type" placeholder="Report Type" value="<?php echo $reportData['type']; ?>" required>
                <input type="datetime-local" name="date" value="<?php echo $reportData['date']; ?>" required>
                <textarea name="content" placeholder="Content (or leave blank if uploading a file)"><?php echo $reportData['content']; ?></textarea>
                <input type="file" name="contentFile" accept=".txt,.pdf,.doc,.docx">
                <button type="submit" name="saveReport"><?php echo $reportData['reportID'] ? 'Update Report' : 'Add Report'; ?></button>
            </form>
        </div>

        <div class="table-container">
            <h2>Report List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Content</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['reportID']; ?></td>
                            <td><?php echo $row['type']; ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo is_file($row['content']) ? '<a href="' . $row['content'] . '" target="_blank">View File</a>' : $row['content']; ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="searchTerm" value="<?php echo $row['reportID']; ?>">
                                    <button type="submit" name="searchReport">Edit</button>
                                </form>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="reportID" value="<?php echo $row['reportID']; ?>">
                                    <button type="submit" name="deleteReport" onclick="return confirm('Are you sure you want to delete this report?');">Delete</button>
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
