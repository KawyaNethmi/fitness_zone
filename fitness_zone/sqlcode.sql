-- Create the database
CREATE DATABASE fitness_zone;

-- Use the newly created database
USE fitness_zone;

-- Create the staff table
CREATE TABLE IF NOT EXISTS staff (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for each staff member
    username VARCHAR(255) NOT NULL,    -- Username of the staff member
    password VARCHAR(255) NOT NULL     -- Password of the staff member
);

-- Create the trainers table
CREATE TABLE IF NOT EXISTS trainers (
    trainerID INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each trainer
    name VARCHAR(255) NOT NULL,               -- Full name of the trainer
    specialization VARCHAR(255) NOT NULL,    -- Trainer's specialization
    phone VARCHAR(15) NOT NULL,               -- Trainer's phone number
    email VARCHAR(255) NOT NULL,              -- Trainer's email
    salary DECIMAL(10, 2) NOT NULL            -- Trainer's salary
);

-- Create the maintainers table
CREATE TABLE IF NOT EXISTS maintainers (
    maintainerID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

-- Create the suppliers table
CREATE TABLE IF NOT EXISTS suppliers (
    supplierID INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each supplier
    username VARCHAR(255) NOT NULL,            -- Username of the supplier
    password VARCHAR(255) NOT NULL,            -- Hashed password for secure storage
    phoneNumber VARCHAR(15) NOT NULL           -- Contact phone number of the supplier
);

-- Create the members table
CREATE TABLE IF NOT EXISTS members (
    memberID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    joinDate DATE NOT NULL
);

-- Create the announcements table
CREATE TABLE IF NOT EXISTS announcements (
    announcementID INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    date DATE NOT NULL
);

-- Create the attendance table
CREATE TABLE IF NOT EXISTS attendance (
    attendanceID INT AUTO_INCREMENT PRIMARY KEY,
    memberID INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('Present', 'Absent') NOT NULL,
    FOREIGN KEY (memberID) REFERENCES members(memberID) ON DELETE CASCADE
);

-- Create the classes table
CREATE TABLE IF NOT EXISTS classes (
    classID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('active', 'inactive') NOT NULL
);

-- Create the shifts table
CREATE TABLE IF NOT EXISTS shifts (
    shiftScheduleID INT AUTO_INCREMENT PRIMARY KEY,
    trainerID INT NOT NULL,
    maintainerID INT NOT NULL,
    time TIME NOT NULL,
    shiftType ENUM('Morning', 'Afternoon', 'Evening') NOT NULL,
    FOREIGN KEY (trainerID) REFERENCES trainers(trainerID) ON DELETE CASCADE,
    FOREIGN KEY (maintainerID) REFERENCES maintainers(maintainerID) ON DELETE CASCADE
);

-- Create the cleaners table
CREATE TABLE IF NOT EXISTS cleaners (
    cleanerID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);

-- Create the memberships table
CREATE TABLE IF NOT EXISTS memberships (
    MembershipID INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

-- Create the equipment table
CREATE TABLE IF NOT EXISTS equipment (
    equipmentID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    conditionn VARCHAR(50) NOT NULL
);

-- Create the expense table
CREATE TABLE IF NOT EXISTS expense_table (
    expenseID INT AUTO_INCREMENT PRIMARY KEY,
    amount DECIMAL(10, 2) NOT NULL,
    description VARCHAR(255) NOT NULL,
    date DATE NOT NULL
);

-- Create the feedback table
CREATE TABLE IF NOT EXISTS feedback (
    feedbackID INT AUTO_INCREMENT PRIMARY KEY,
    memberID INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the gym schedule table
CREATE TABLE IF NOT EXISTS gymschedule (
    scheduleID INT AUTO_INCREMENT PRIMARY KEY,
    day VARCHAR(20) NOT NULL,
    time TIME NOT NULL,
    activity VARCHAR(255) NOT NULL
);

-- Create the equipment orders table
CREATE TABLE IF NOT EXISTS equipment_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    supplierID INT NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplierID) REFERENCES suppliers(supplierID) ON DELETE CASCADE
);

-- Create the payment history table
CREATE TABLE IF NOT EXISTS payment_history (
    invoiceID INT AUTO_INCREMENT PRIMARY KEY,
    memberID INT NOT NULL,
    paymentMethod VARCHAR(50) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    paymentDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Completed', 'Pending', 'Failed') DEFAULT 'Completed',
    FOREIGN KEY (memberID) REFERENCES members(memberID) ON DELETE CASCADE
);

-- Create the membership purchases table
CREATE TABLE IF NOT EXISTS membership_purchases (
    purchaseID INT AUTO_INCREMENT PRIMARY KEY,
    memberID INT NOT NULL,  -- Assuming you have a members table
    membershipType ENUM('basic', 'premium', 'vip') NOT NULL,
    cardNumber VARCHAR(16) NOT NULL,  -- Store card number securely, consider encryption
    expiryDate VARCHAR(5) NOT NULL,  -- Format: MM/YY
    cvv VARCHAR(3) NOT NULL,  -- CVV code
    billingAddress VARCHAR(255) NOT NULL,
    purchaseDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Successful', 'Failed') DEFAULT 'Successful',
    FOREIGN KEY (memberID) REFERENCES members(memberID) ON DELETE CASCADE
);

-- Create the reports table
CREATE TABLE IF NOT EXISTS reports (
    reportID INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(255) NOT NULL,
    date DATETIME NOT NULL,
    content TEXT NOT NULL
);
CREATE TABLE tbtrainers (
    trainerID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    salary DECIMAL(10, 2) NOT NULL
);

-- Insert data into the 'staff' table
INSERT INTO staff (username, password) VALUES
('Admin', '123'),
('Expense Manager', '123'),
('Equipment Manager', '123'),
('Cleaner', '123'),
('Maintainer', '123'),
('Trainer', '123');
