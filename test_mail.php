<?php
// Standalone test script to verify email delivery independent of OTP flow
// Usage: http://localhost/vsoftstaticphp/test_mail.php?to=your@email.com

require __DIR__ . '/config/mail.php';

$to = isset($_GET['to']) ? trim($_GET['to']) : '';
if ($to === '') {
    header('Content-Type: text/plain');
    echo "Usage: test_mail.php?to=recipient@example.com\n";
    echo "Reads SMTP settings from config/.env or project .env.\n";
    exit;
}

$subject = 'Test Email from VSoft App';
$body = '<p>This is a <strong>test email</strong> sent at ' . date('Y-m-d H:i:s') . '.</p>';

$ok = false;
try {
    $ok = sendMail($to, $subject, $body);
} catch (Throwable $e) {
    error_log('[test_mail] Exception: ' . $e->getMessage());
}

header('Content-Type: application/json');
echo json_encode([
    'to' => $to,
    'success' => (bool)$ok,
    'hint' => $ok ? 'Delivery attempted; check your inbox/spam.' : 'Mail send failed. Check PHP error log for details.',
]);
