<?php
class PasswordResetModel
{
    private $conn;
    private $table = "password_resets";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create or replace a token for an email
    public function createToken($email, $token)
    {
        // Delete existing tokens for this email
        $del = $this->conn->prepare("DELETE FROM {$this->table} WHERE email = ?");
        $del->bind_param("s", $email);
        $del->execute();

        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (email, token, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $email, $token);
        return $stmt->execute();
    }

    // Verify if token matches and is not expired (expiryMinutes)
    public function verifyToken($email, $token, $expiryMinutes = 10)
    {
        $stmt = $this->conn->prepare("SELECT token, created_at FROM {$this->table} WHERE email = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if (!$row) return false;

        if (!hash_equals($row['token'], $token)) {
            return false;
        }

        // Check expiry
        $created = strtotime($row['created_at']);
        if ($created === false) return false;
        $ageSeconds = time() - $created;
        return $ageSeconds <= ($expiryMinutes * 60);
    }

    // Consume token(s) for an email after successful reset
    public function consumeToken($email)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE email = ?");
        $stmt->bind_param("s", $email);
        return $stmt->execute();
    }
}
