<?php
session_start();

$servername = "172.20.54.101";
$username = "nourchene";
$password = "abcd12";
$dbname = "password_manager";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $action = $_POST['action'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($action == 'signup') {
            $email = $_POST['email'];
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            if ($stmt->execute()) {
                echo "Signup successful!";
            } else {
                echo "Error: " . $stmt->errorInfo()[2];
            }
        } elseif ($action == 'signin') {
            $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    echo "Signin successful!";
                } else {
                    echo "Invalid username or password.";
                }
            } else {
                echo "Invalid username or password.";
            }
        }
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
