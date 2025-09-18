<?php 
include 'db_connection.php'; // Include the database connection file

// Initialize variables
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : "";
$message = "";
$memberData = [
    'memberID' => '',
    'username' => '',
    'password' => '',
    'age' => '',
    'gender' => '',
    'phone' => '',
    'email' => '',
    'address' => '',
    'joinDate' => ''
];

// Redirect to admin_dashboard.php when exit button is clicked
if (isset($_POST['exit'])) {
    header("Location: admin_dashboard.php");
    exit();
}

// Handle Add or Update Member
if (isset($_POST['saveMember'])) {
    $memberID = $_POST['memberID'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $joinDate = $_POST['joinDate'];

    if ($memberID) {
        // Update Member
        $sql = "UPDATE members SET username = ?, password = ?, age = ?, gender = ?, phone = ?, email = ?, address = ?, joinDate = ? WHERE memberID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisssssi", $username, $password, $age, $gender, $phone, $email, $address, $joinDate, $memberID);
    } else {
        // Add new Member
        $sql = "INSERT INTO members (username, password, age, gender, phone, email, address, joinDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisssss", $username, $password, $age, $gender, $phone, $email, $address, $joinDate);
    }

    if ($stmt->execute()) {
        $message = $memberID ? "Member updated successfully!" : "Member added successfully!";
        $memberData = [
            'memberID' => '',
            'username' => '',
            'password' => '',
            'age' => '',
            'gender' => '',
            'phone' => '',
            'email' => '',
            'address' => '',
            'joinDate' => ''
        ];
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Search by Member ID
if (isset($_POST['searchMember']) && $searchTerm) {
    $sql = "SELECT * FROM members WHERE memberID LIKE ?";
    $searchParam = "%$searchTerm%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $memberData = $row;
    } else {
        $message = "Member not found!";
    }
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM members");
}

// Handle Delete Member
if (isset($_POST['deleteMember'])) {
    $memberID = $_POST['memberID'];
    $sql = "DELETE FROM members WHERE memberID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $memberID);
    if ($stmt->execute()) {
        $message = "Member deleted successfully!";
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
    <title>Manage Members</title>
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
    width: 8px; /* Width of the vertical scrollbar */
}

body::-webkit-scrollbar-thumb {
    background-color: #ff6a00; /* Color of the scrollbar thumb */
    border-radius: 10px;
}

body::-webkit-scrollbar-track {
    background: #f1f1f1; /* Background color of the track */
}

/* Dynamic Shape Animation (circles floating in the background) */
.circle {
    position: absolute;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.2);
    animation: moveCircles 10s infinite linear;
    z-index: -1;
}

/* Animation for moving circles */
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

/* Create multiple circles with random sizes and positions */
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
    <!-- Dynamic Circles for Background Animation -->
    <div class="circle circle1"></div>
    <div class="circle circle2"></div>
    <div class="circle circle3"></div>
    <div class="circle circle4"></div>

    <header>
        <h1>Manage Members</h1>
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
            <h2>Search Member</h2>
            <form method="post">
                <input type="text" name="searchTerm" placeholder="Search by Member ID" value="<?php echo $searchTerm; ?>">
                <button type="submit" name="searchMember">Search</button>
            </form>
        </div>

        <!-- Add/Update Member Form -->
        <div class="form-container">
            <h2><?php echo $memberData['memberID'] ? 'Update Member' : 'Add Member'; ?></h2>
            <form method="post">
                <input type="hidden" name="memberID" value="<?php echo $memberData['memberID']; ?>">
                <input type="text" name="username" placeholder="Username" value="<?php echo $memberData['username']; ?>" required>
                <input type="password" name="password" placeholder="Password" value="<?php echo $memberData['password']; ?>" required>
                <input type="number" name="age" placeholder="Age" value="<?php echo $memberData['age']; ?>" required>
                <select name="gender" required>
                    <option value="Male" <?php echo $memberData['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $memberData['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo $memberData['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
                <input type="text" name="phone" placeholder="Phone" value="<?php echo $memberData['phone']; ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?php echo $memberData['email']; ?>" required>
                <textarea name="address" placeholder="Address" required><?php echo $memberData['address']; ?></textarea>
                <input type="date" name="joinDate" placeholder="Join Date" value="<?php echo $memberData['joinDate']; ?>" required>
                <button type="submit" name="saveMember"><?php echo $memberData['memberID'] ? 'Update Member' : 'Add Member'; ?></button>
            </form>
        </div>

        <!-- Members Table -->
        <div class="table-container">
            <h2>Members List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Username</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Join Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['memberID']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['joinDate']; ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="searchTerm" value="<?php echo $row['memberID']; ?>">
                                    <button type="submit" name="searchMember">Edit</button>
                                </form>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="memberID" value="<?php echo $row['memberID']; ?>">
                                    <button type="submit" name="deleteMember" onclick="return confirm('Are you sure you want to delete this member?');">Delete</button>
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
