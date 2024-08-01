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

$username = $_SESSION['username'];

$sql = "SELECT * FROM passwords WHERE username='$username'";
$result = $conn->query($sql);

$passwords = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $encrypted_password = base64_decode($row['password_password']);  // Decode from Base64
        $decrypted_password = xor_this($encrypted_password);  // Decrypt password with XOR
        $passwords[] = [
            'id' => $row['id'],
            'website' => $row['website'],
            'username' => $row['password_username'],
            'password' => $decrypted_password
        ];
    }
    echo json_encode(['success' => true, 'passwords' => $passwords]);
} else {
    echo json_encode(['success' => false, 'message' => 'No passwords stored']);
}

$conn->close();
?>
