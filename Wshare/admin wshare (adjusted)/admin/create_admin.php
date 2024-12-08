<?php
require_once '../db_connection.php'; // Changed path to go up one directory

// Add IsAdmin column if it doesn't exist
$alterTable = "ALTER TABLE Users ADD COLUMN IF NOT EXISTS IsAdmin TINYINT(1) DEFAULT 0";
$conn->query($alterTable);

// Create admin user
$username = 'wshare_admin';
$email = 'admin@wshare.com';
$password = 'admin123'; // Change this to your desired password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO Users (Username, Email, Password, IsAdmin) 
        VALUES (?, ?, ?, 1)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $hashedPassword);

if ($stmt->execute()) {
    echo "Admin user created successfully!";
} else {
    echo "Error creating admin user: " . $conn->error;
}
?>