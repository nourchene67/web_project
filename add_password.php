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


$username = $_SESSION['username'];
$website = $_POST['website'];
$password_username = $_POST['username'];

$encrypted_password = xor_this($_POST['password']);
$base64_password = base64_encode($encrypted_password);

$sql = "INSERT INTO passwords (username, website, password_username, password_password) VALUES ('$username', '$website', '$password_username', '$base64_password')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $sql . '<br>' . $conn->error]);
}

$conn->close();
?>
