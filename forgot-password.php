<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "my_database"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

$email = $data['email'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $update_sql = "UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sss", $token, $expires, $email);
    $update_stmt->execute();

  
    echo json_encode([
        "success" => true, 
        "message" => "Password reset instructions sent. Use this token to reset your password: " . $token
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Email not found"]);
}

$stmt->close();
$conn->close();
?>
