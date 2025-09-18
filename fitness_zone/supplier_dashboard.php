<?php
include 'db_connection.php'; // Include the database connection file

// Initialize variables
$orderData = [
    'id' => '',
    'equipment_name' => '',
    'quantity' => '',
    'supplierID' => '',
    'status' => 'Pending',
    'order_date' => ''
];
$message = '';

// Handle Search by Order ID
if (isset($_POST['searchOrder']) && isset($_POST['searchTerm'])) {
    $searchTerm = $_POST['searchTerm'];
    $sql = "SELECT * FROM equipment_orders WHERE id LIKE ?";
    $searchParam = "%$searchTerm%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $orderData = $row;
    } else {
        $message = "Order not found!";
    }
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM equipment_orders");
}

// Handle Approve or Reject Order
if (isset($_POST['approve']) || isset($_POST['reject'])) {
    $orderID = $_POST['orderID'];
    $newStatus = isset($_POST['approve']) ? 'Approved' : 'Rejected';

    // Update the order status
    $updateSql = "UPDATE equipment_orders SET status = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $newStatus, $orderID);
    
    if ($updateStmt->execute()) {
        $message = "Order status updated successfully.";
    } else {
        $message = "Error updating order status: " . $updateStmt->error;
    }
    $updateStmt->close();
    header("Location: supplier_dashboard.php"); 
    exit();
}

// Handle Exit button
if (isset($_POST['exit'])) {
    header("Location: home.php"); // Redirect to the admin dashboard
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Equipment Orders</title>
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
        }    </style>
</head>
<body>
<div class="circle circle1"></div>
    <div class="circle circle2"></div>
    <div class="circle circle3"></div>
    <div class="circle circle4"></div>
    <header>
        <h1>ORDERS</h1>
        <form method="post">
            <button type="submit" class="exit-button" name="exit">Exit</button>
        </form>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Search Equipment Orders Form -->
        <div class="form-container">
            <h2>Search Order</h2>
            <form method="post">
                <input type="text" name="searchTerm" placeholder="Search by Order ID" value="<?php echo isset($searchTerm) ? $searchTerm : ''; ?>">
                <button type="submit" name="searchOrder">Search</button>
            </form>
        </div>

        <!-- Equipment Orders Table -->
        <div class="table-container">
            <h2>Equipment Orders List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Equipment Name</th>
                        <th>Quantity</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['equipment_name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>
                                <?php
                                // Fetch supplier username based on supplierID
                                $supplierResult = $conn->query("SELECT username FROM suppliers WHERE supplierID = " . $row['supplierID']);
                                $supplier = $supplierResult->fetch_assoc();
                                echo $supplier['username'];
                                ?>
                            </td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'Pending'): ?>
                                    <!-- Only show buttons for Pending orders -->
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="orderID" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="approve">Approve</button>
                                        <button type="submit" name="reject">Reject</button>
                                    </form>
                                <?php else: ?>
                                    <!-- Hide buttons if status is Approved or Rejected -->
                                    <button disabled>Action Taken</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
