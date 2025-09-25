<?php
session_start();
require './config/database.php';

$db = new Database();
$conn = $db->connect();

$token = $_GET['token'] ?? '';
$message = '';

if ($token) {
    $stmt = $conn->prepare("SELECT id, email_verified_at FROM users WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $email_verified_at);
        $stmt->fetch();
        if ($email_verified_at !== null) {
            $message = "Your email is already verified.";
        } else {
            $now = date("Y-m-d H:i:s");
            $stmt2 = $conn->prepare("
                UPDATE users
                SET email_verified_at = ?, verification_token = NULL
                WHERE id = ?
            ");
            $stmt2->bind_param("si", $now, $user_id);
            if ($stmt2->execute()) {
                $message = "Email verified successfully! You may now log in.";
            } else {
                $message = "Verification failed. Please try again.";
            }
            $stmt2->close();
        }
    } else {
        $message = "Invalid verification token.";
    }
    $stmt->close();
} else {
    $message = "No verification token provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>
<body>
    <p><?php echo htmlspecialchars($message); ?></p>
    <p><a href="login.php">Go to Login</a></p>
</body>
</html>
