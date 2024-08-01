<?php
session_start();

function xor_this($string) {
    $key = 'magic_key';
    $text = $string;
    $outText = '';
    for($i = 0; $i < strlen($text); ) {
        for($j = 0; ($j < strlen($key) && $i < strlen($text)); $j++, $i++) {
            $outText .= $text[$i] ^ $key[$j];
        }
    }
    return $outText;
}

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$conn = new mysqli('172.20.54.101', 'nourchene', 'abcd12', 'password_manager');

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

$id = $_POST['id'];
$encrypted_password = xor_this($_POST['password']);
$base64_password = base64_encode($encrypted_password);  // Encode to Base64

$sql = "UPDATE passwords SET password_password='$base64_password' WHERE id='$id' AND username='{$_SESSION['username']}'";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
}

$conn->close();
?>
