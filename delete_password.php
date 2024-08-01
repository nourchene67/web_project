<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$conn = new mysqli('172.20.54.101', 'nourchene', 'abcd12', 'password_manager');

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

$id = $_GET['id'];

$sql = "DELETE FROM passwords WHERE id='$id' AND username='{$_SESSION['username']}'";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
}

$conn->close();
?>

