<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$servername = "localhost";
$username = "root";  // default XAMPP username
$password = "";      // default XAMPP password (empty)
$dbname = "my_database"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

$name = $data['name'];
$email = $data['email'];
$password = $data['password'];
$confirm_password = $data['confirm_password'];
$dob = $data['dob'];
$age = $data['age'];
$job = $data['job'];
$address = $data['address'];
$phone = $data['phone'];


if ($password !== $confirm_password) {
    echo json_encode(["success" => false, "message" => "Passwords do not match"]);
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name, email, password, dob, age, job, address, phone) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssisss", $name, $email, $hashed_password, $dob, $age, $job, $address, $phone);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "User registered successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
