<?php
include 'db_connection.php'; // Include the database connection file

// Initialize variables
$paymentData = [
    'invoiceID' => '',
    'memberID' => '',
    'paymentMethod' => '',
    'amount' => '',
    'paymentDate' => '',
    'status' => 'Completed'
];
$message = '';

// Handle Add or Update Payment History
if (isset($_POST['savePayment'])) {
    $invoiceID = $_POST['invoiceID'];
    $memberID = $_POST['memberID'];
    $paymentMethod = $_POST['paymentMethod'];
    $amount = $_POST['amount'];
    $paymentDate = $_POST['paymentDate'];
    $status = $_POST['status'];

    if ($invoiceID) {
        // Update Payment History
        $sql = "UPDATE payment_history SET memberID = ?, paymentMethod = ?, amount = ?, paymentDate = ?, status = ? WHERE invoiceID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdsi", $memberID, $paymentMethod, $amount, $paymentDate, $status, $invoiceID);
    } else {
        // Add new Payment History
        $sql = "INSERT INTO payment_history (memberID, paymentMethod, amount, paymentDate, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issds", $memberID, $paymentMethod, $amount, $paymentDate, $status);
    }

    if ($stmt->execute()) {
        $message = $invoiceID ? "Payment updated successfully!" : "Payment added successfully!";
        $paymentData = [
            'invoiceID' => '',
            'memberID' => '',
            'paymentMethod' => '',
            'amount' => '',
            'paymentDate' => '',
            'status' => 'Completed'
        ];
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Search by invoiceID
if (isset($_POST['searchPayment']) && isset($_POST['searchTerm'])) {
    $searchTerm = $_POST['searchTerm'];
    $sql = "SELECT * FROM payment_history WHERE invoiceID LIKE ?";
    $searchParam = "%$searchTerm%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $paymentData = $row;
    } else {
        $message = "Payment not found!";
    }
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM payment_history");
}

// Handle Delete Payment History
if (isset($_POST['deletePayment'])) {
    $invoiceID = $_POST['invoiceID'];
    $sql = "DELETE FROM payment_history WHERE invoiceID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $invoiceID);
    if ($stmt->execute()) {
        $message = "Payment deleted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Exit button
if (isset($_POST['exit'])) {
    header("Location: admin_dashboard.php"); // Redirect to the admin dashboard
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payment History</title>
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

    <!-- Dynamic Circles -->
    <div class="circle circle1"></div>
    <div class="circle circle2"></div>
    <div class="circle circle3"></div>
    <div class="circle circle4"></div>

    <header>
        <h1>Manage Payment History</h1>
        <form method="post">
            <button type="submit" class="exit-button" name="exit">Exit</button>
        </form>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Search Payment History Form -->
        <div class="form-container">
            <h2>Search Payment</h2>
            <form method="post">
                <input type="text" name="searchTerm" placeholder="Search by Invoice ID" value="<?php echo isset($searchTerm) ? $searchTerm : ''; ?>">
                <button type="submit" name="searchPayment">Search</button>
            </form>
        </div>

        <!-- Add/Update Payment History Form -->
        <div class="form-container">
            <h2><?php echo $paymentData['invoiceID'] ? 'Update Payment' : 'Add Payment'; ?></h2>
            <form method="post">
                <input type="hidden" name="invoiceID" value="<?php echo $paymentData['invoiceID']; ?>">
                <select name="memberID" required>
                    <option value="">Select Member</option>
                    <?php
                    $membersResult = $conn->query("SELECT memberID, username FROM members");
                    while ($member = $membersResult->fetch_assoc()) {
                        echo "<option value='{$member['memberID']}'" . ($paymentData['memberID'] == $member['memberID'] ? ' selected' : '') . ">{$member['username']}</option>";
                    }
                    ?>
                </select>
                <input type="text" name="paymentMethod" placeholder="Payment Method" value="<?php echo $paymentData['paymentMethod']; ?>" required>
                <input type="number" step="0.01" name="amount" placeholder="Amount" value="<?php echo $paymentData['amount']; ?>" required>
                <input type="datetime-local" name="paymentDate" value="<?php echo date('Y-m-d\TH:i', strtotime($paymentData['paymentDate'])); ?>" required>
                <select name="status">
                    <option value="Completed" <?php echo $paymentData['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="Pending" <?php echo $paymentData['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Failed" <?php echo $paymentData['status'] == 'Failed' ? 'selected' : ''; ?>>Failed</option>
                </select>
                <button type="submit" name="savePayment">Save Payment</button>
            </form>
        </div>

        <!-- Payment History Table -->
        <div class="table-container">
            <h2>Payment History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Member</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['invoiceID']; ?></td>
                            <td><?php 
                                $memberResult = $conn->query("SELECT username FROM members WHERE memberID = {$row['memberID']}");
                                $member = $memberResult->fetch_assoc();
                                echo $member['username'];
                            ?></td>
                            <td><?php echo $row['paymentMethod']; ?></td>
                            <td><?php echo $row['amount']; ?></td>
                            <td><?php echo $row['paymentDate']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="invoiceID" value="<?php echo $row['invoiceID']; ?>">
                                    <button type="submit" name="deletePayment" onclick="return confirm('Are you sure you want to delete this payment?');">Delete</button>
                                </form>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="invoiceID" value="<?php echo $row['invoiceID']; ?>">
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
