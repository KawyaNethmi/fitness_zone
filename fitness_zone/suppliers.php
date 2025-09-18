<?php 
include 'db_connection.php'; // Include the database connection file

// Initialize variables
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : "";
$message = "";
$supplierData = [
    'supplierID' => '',
    'username' => '',
    'password' => '',
    'phoneNumber' => ''
];

// Redirect to admin_dashboard.php when exit button is clicked
if (isset($_POST['exit'])) {
    header("Location: admin_dashboard.php");
    exit();
}

// Handle Add or Update Supplier
if (isset($_POST['saveSupplier'])) {
    $supplierID = $_POST['supplierID'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $phoneNumber = $_POST['phoneNumber'];

    if ($supplierID) {
        // Update Supplier
        $sql = "UPDATE suppliers SET username = ?, password = ?, phoneNumber = ? WHERE supplierID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $password, $phoneNumber, $supplierID);
    } else {
        // Add new Supplier
        $sql = "INSERT INTO suppliers (username, password, phoneNumber) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $phoneNumber);
    }

    if ($stmt->execute()) {
        $message = $supplierID ? "Supplier updated successfully!" : "Supplier added successfully!";
        $supplierData = [
            'supplierID' => '',
            'username' => '',
            'password' => '',
            'phoneNumber' => ''
        ];
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Search by Supplier ID
if (isset($_POST['searchSupplier']) && $searchTerm) {
    $sql = "SELECT * FROM suppliers WHERE supplierID LIKE ?";
    $searchParam = "%$searchTerm%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $supplierData = $row;
    } else {
        $message = "Supplier not found!";
    }
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM suppliers");
}

// Handle Delete Supplier
if (isset($_POST['deleteSupplier'])) {
    $supplierID = $_POST['supplierID'];
    $sql = "DELETE FROM suppliers WHERE supplierID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $supplierID);
    if ($stmt->execute()) {
        $message = "Supplier deleted successfully!";
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
    <title>Manage Suppliers</title>
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
        <h1>Suppliers</h1>
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
            <h2>Search Supplier</h2>
            <form method="post">
                <input type="text" name="searchTerm" placeholder="Search by Supplier ID" value="<?php echo $searchTerm; ?>">
                <button type="submit" name="searchSupplier">Search</button>
            </form>
        </div>

        <!-- Add/Update Supplier Form -->
        <div class="form-container">
            <h2><?php echo $supplierData['supplierID'] ? 'Update Supplier' : 'Add Supplier'; ?></h2>
            <form method="post">
                <input type="hidden" name="supplierID" value="<?php echo $supplierData['supplierID']; ?>">
                <input type="text" name="username" placeholder="Username" value="<?php echo $supplierData['username']; ?>" required>
                <input type="text" name="password" placeholder="Password" value="<?php echo $supplierData['password']; ?>" required>
                <input type="text" name="phoneNumber" placeholder="Phone Number" value="<?php echo $supplierData['phoneNumber']; ?>" required>
                <button type="submit" name="saveSupplier"><?php echo $supplierData['supplierID'] ? 'Update Supplier' : 'Add Supplier'; ?></button>
            </form>
        </div>

        <!-- Suppliers Table -->
        <div class="table-container">
            <h2>Suppliers List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Supplier ID</th>
                        <th>Username</th>
                        <th>Phone Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['supplierID']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['phoneNumber']; ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="searchTerm" value="<?php echo $row['supplierID']; ?>">
                                    <button type="submit" name="searchSupplier">Edit</button>
                                </form>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="supplierID" value="<?php echo $row['supplierID']; ?>">
                                    <button type="submit" name="deleteSupplier" onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</button>
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
