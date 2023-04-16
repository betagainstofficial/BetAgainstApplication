<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
// Start a new session or resume an existing one
session_start();
// Redirect to index page if user is already logged in
if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
 }

// Redirect to index page if user is already logged in
if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
 }

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

    // Hash and salt the password
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username is already taken
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Username is already taken
        echo 'Username is already taken. Please choose a different username.';
    } else {
        // Username is available, insert the new user into the "users" table
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hash')";
        if ($conn->query($sql) === TRUE) {
            // New user has been added to the "users" table
            $_SESSION['username'] = $username;
            echo 'You have successfully signed up! Welcome, ' . $username . '!';
        } else {
            // Error inserting user into the "users" table
            echo 'Error: ' . $sql . '<br>' . $conn->error;
        }
    }
}

// Close the MySQL connection
$conn->close();
?>

