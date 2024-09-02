<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "my_database"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
  
    $receipt = "User Registration Receipt\n";
    $receipt .= "==========================\n\n";
    $receipt .= "User ID: " . $user['id'] . "\n";
    $receipt .= "Full Name: " . $user['name'] . "\n";
    $receipt .= "Email: " . $user['email'] . "\n";
    $receipt .= "Date of Birth: " . $user['dob'] . "\n";
    $receipt .= "Age: " . $user['age'] . "\n";
    $receipt .= "Job: " . $user['job'] . "\n";
    $receipt .= "Address: " . $user['address'] . "\n";
    $receipt .= "Phone Number: " . $user['phone'] . "\n";
    $receipt .= "Registration Date: " . $user['created_at'] . "\n";
    

    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="user_receipt.txt"');
   
    echo $receipt;
} else {
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>
