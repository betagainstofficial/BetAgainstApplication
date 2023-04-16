<?php
require_once __DIR__ . '/vendor/autoload.php';
// Start a new session or resume an existing one
session_start();

// Connect to the MySQL server
$host = getenv('MYSQL_HOST');  // Your MySQL host name
$user = 'root';       // Your MySQL username
$pass = getenv('DB_PASSWORD');           // Your MySQL password
$db = 'bettingapp';   // The name of your MySQL database

$conn = new mysqli($host, $user, $pass, $db);

// Check for errors
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists in the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        // Username exists, check the password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, log in the user
            echo 'You have successfully logged in!';
        } else {
            // Password is incorrect
            echo 'Incorrect password. Please try again.';
        }
    } else {
        // Username does not exist
        echo 'Username does not exist. Please try again.';
    }
}

// Close the MySQL connection
$conn->close();
?>