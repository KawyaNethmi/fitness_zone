<?php
include 'db_connection.php'; // Include the database connection file

// Initialize variables
$message = "";

// Redirect to home if exit button is clicked
if (isset($_POST['exit'])) {
    header("Location: member_dashboard.php");
    exit();
}

// Handle Membership Purchase
if (isset($_POST['purchaseMembership'])) {
    // Get the data from the form
    $memberID = $_POST['memberID'];
    $membershipType = $_POST['membershipType'];
    $cardNumber = $_POST['cardNumber'];
    $expiryDate = $_POST['expiryDate'];
    $cvv = $_POST['cvv'];
    $billingAddress = $_POST['billingAddress'];

    // Validate the card information (this could be enhanced with more robust validation)
    if (empty($memberID) || empty($membershipType) || empty($cardNumber) || empty($expiryDate) || empty($cvv) || empty($billingAddress)) {
        $message = "Please fill in all fields.";
    } else {
        // Insert membership purchase into the database
        $sql = "INSERT INTO membership_purchases (memberID, membershipType, cardNumber, expiryDate, cvv, billingAddress) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $memberID, $membershipType, $cardNumber, $expiryDate, $cvv, $billingAddress);

        if ($stmt->execute()) {
            $message = "Membership purchase successful!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Membership</title>
    <style>
        /* Add your styling here */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(45deg, #ff6a00, #ff1493);
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 600px;
            margin-top: 50px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select, button {
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }
        button {
            background-color: #ff6a00;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #ff1493;
        }
        .message {
            text-align: center;
            font-size: 1.2rem;
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Purchase Membership</h2>
        
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <form method="post">
            <input type="number" name="memberID" placeholder="Member ID" required>
            <select name="membershipType" required>
                <option value="">Select Membership Type</option>
                <option value="basic">Basic</option>
                <option value="premium">Premium</option>
                <option value="vip">VIP</option>
            </select>
            <input type="text" name="cardNumber" placeholder="Card Number" maxlength="16" required>
            <input type="text" name="expiryDate" placeholder="Expiry Date (MM/YY)" maxlength="5" required>
            <input type="text" name="cvv" placeholder="CVV" maxlength="3" required>
            <input type="text" name="billingAddress" placeholder="Billing Address" required>
            <button type="submit" name="purchaseMembership">Purchase Membership</button>
        </form>

        <form method="post" style="margin-top: 20px;">
            <button type="submit" name="exit">Exit</button>
        </form>
    </div>
</body>
</html>
