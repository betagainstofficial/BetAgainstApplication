<?php
require_once __DIR__ . '/vendor/autoload.php';
// Start a new session or resume an existing one
session_start();

// Redirect to index page if user is already logged in
if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
 }
// Connect to the MySQL server
$host = getenv('MYSQL_HOST');  // Your MySQL host name
$user = 'root';       // Your MySQL username
$pass = getenv('DB_PASSWORD');   // Your MySQL password
$db = 'bettingapp';   // The name of your MySQL database
$port = 3306; // The port number used by MySQL

$conn = new mysqli($host, $user, $pass, $db);

// Check for errors
if ($conn->connect_error) {
   die('Connection failed: ' . $conn->connect_error);
}

// Get the username and password from the login form
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the username and password match a row in the "users" table
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
   // Username exists, check if password is correct
   $row = $result->fetch_assoc();
   if (password_verify($password, $row['password'])) {
      // Password is correct, start a new session for the user
      $_SESSION['username'] = $username;
      echo 'You have successfully logged in!';
   } else {
      // Password is incorrect
      echo 'Incorrect password. Please try again.';
   }
} else {
   // Username does not exist
   echo 'Username does not exist. Please try again.';
}

// Close the MySQL connection
$conn->close();
?>
