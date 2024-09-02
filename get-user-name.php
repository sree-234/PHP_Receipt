<?php
session_start();
header("Content-Type: application/json");

if (isset($_SESSION['user_name'])) {
    echo json_encode(["name" => $_SESSION['user_name']]);
} else {
    echo json_encode(["name" => "Guest"]);
}
?>
