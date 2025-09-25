<?php
include __DIR__ . '/config/database.php';

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("Database connection failed.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Please enter a valid email.'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
    if (!$stmt) {
        echo "<script>alert('Database error.'); window.history.back();</script>";
        exit;
    }

    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        echo "<script>alert('Thank you for subscribing!'); window.location.href='index.php';</script>";
        exit;
    } else {
        if ($stmt->errno == 1062) {
            echo "<script>alert('This email is already subscribed.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Subscription failed: " . addslashes($stmt->error) . "'); window.location.href='index.php';</script>";
        }
        exit;
    }
    $stmt->close();
}
$conn->close();