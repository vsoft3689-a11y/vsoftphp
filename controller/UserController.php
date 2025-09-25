<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
header("Content-Type: application/json; charset=utf-8");
session_start();

include __DIR__ . "/../config/database.php";
include __DIR__ . "/../config/mail.php";
include __DIR__ . "/../model/UserModel.php";
include __DIR__ . "/../model/PasswordResetModel.php";

$action = $_REQUEST['action'] ?? '';

$controller = new UserController();
$controller->handleRequest($action);

class UserController
{
    private $db;
    private $userModel;
    private $resetModel;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->userModel = new UserModel($this->db);
        $this->resetModel = new PasswordResetModel($this->db);
    }

    public function handleRequest($action)
    {
        switch ($action) {
            case "create":

                $data = [
                    "name"     => $_POST['name'],
                    "email"    => $_POST['email'],
                    "password" => $_POST['password'],
                    "phone"    => $_POST['phone'] ?? null,
                    "college"  => $_POST['college'] ?? null,
                    "branch"   => $_POST['branch'] ?? null,
                    "year"     => $_POST['year'] ?? null
                ];

                echo json_encode($this->userModel->create($data));
                break;

            case "read":
                echo json_encode($this->userModel->read());
                break;


            case "getUser":
                $data = [
                    "email" => $_POST['email'] ?? '',
                    "password" => $_POST['password'] ?? ''
                ];
                echo json_encode($this->userModel->getByEmailAndPassword($data));
                break;

            case "update":
                $data = [
                    "id"      => $_POST['id'],
                    "name"    => $_POST['name'],
                    "email"   => $_POST['email'],
                    "phone"   => $_POST['phone'] ?? null,
                    "college" => $_POST['college'] ?? null,
                    "branch"  => $_POST['branch'] ?? null,
                    "year"    => $_POST['year'] ?? null
                ];
                echo json_encode(
                    $this->userModel->update($data) ?
                        ["success" => true, "message" => "Student updated"] :
                        ["success" => false, "message" => "Update failed"]
                );
                break;

            case "delete":
                echo json_encode(
                    $this->userModel->delete($_POST['id']) ?
                        ["success" => true, "message" => "Student deleted"] :
                        ["success" => false, "message" => "Delete failed"]
                );
                break;

            case "toggle_status":
                echo json_encode(
                    $this->userModel->toggleStatus($_POST['id'], $_POST['status']) ?
                        ["success" => true, "message" => "Account status updated"] :
                        ["success" => false, "message" => "Status update failed"]
                );
                break;

            case "verify_email":
                echo json_encode(
                    $this->userModel->verifyEmail($_POST['id']) ?
                        ["success" => true, "message" => "Email verified"] :
                        ["success" => false, "message" => "Failed to verify email"]
                );
                break;

            case "update_password":
                echo json_encode(
                    $this->userModel->updatePassword($_POST['id'], $_POST['password']) ?
                        ["success" => true, "message" => "Password updated"] :
                        ["success" => false, "message" => "Failed to update password"]
                );
                break;

            // Request a password reset: generate and email OTP
            case "request_password_reset":
                $email = trim($_POST['email'] ?? '');
                if ($email === '') {
                    echo json_encode(["success" => false, "message" => "Email is required"]);
                    break;
                }

                // Check user exists
                $exists = $this->userModel->existsByEmail($email);
                if (!$exists) {
                    // For privacy, return success but do nothing
                    echo json_encode(["success" => true, "message" => "If the email exists, an OTP has been sent."]);
                    break;
                }

                // Generate 6-digit OTP
                $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                // DEV ONLY: log OTP to server error log for local testing. Remove in production.
                error_log("[PasswordReset] OTP for {$email}: {$otp}");
                $saved = $this->resetModel->createToken($email, $otp);
                if (!$saved) {
                    echo json_encode(["success" => false, "message" => "Could not initiate password reset. Try again."]);
                    break;
                }

                $subject = "Your Password Reset OTP";
                $bodyHtml = "<p>Your OTP code is <strong>{$otp}</strong>. It expires in 10 minutes.</p>";
                // Try to send the email, but do not block the flow if SMTP fails
                $sent = sendMail($email, $subject, $bodyHtml);
                echo json_encode([
                    "success" => true,
                    "message" => "If the email exists, an OTP has been sent.",
                    "email_sent" => (bool)$sent
                ]);
                break;

            // Verify OTP
            case "verify_otp":
                $email = trim($_POST['email'] ?? '');
                $otp = trim($_POST['otp'] ?? '');
                if ($email === '' || $otp === '') {
                    echo json_encode(["success" => false, "message" => "Email and OTP are required"]);
                    break;
                }
                $valid = $this->resetModel->verifyToken($email, $otp, 10); // 10 minutes expiry
                echo json_encode($valid ? ["success" => true] : ["success" => false, "message" => "Invalid or expired OTP"]);
                break;

            // Reset password using verified OTP
            case "reset_password":
                $email = trim($_POST['email'] ?? '');
                $otp = trim($_POST['otp'] ?? '');
                $password = $_POST['password'] ?? '';
                if ($email === '' || $otp === '' || $password === '') {
                    echo json_encode(["success" => false, "message" => "Email, OTP and password are required"]);
                    break;
                }
                if (!$this->resetModel->verifyToken($email, $otp, 10)) {
                    echo json_encode(["success" => false, "message" => "Invalid or expired OTP"]);
                    break;
                }
                $updated = $this->userModel->updatePasswordByEmail($email, $password);
                if ($updated) {
                    $this->resetModel->consumeToken($email);
                    echo json_encode(["success" => true, "message" => "Password reset successful"]);
                } else {
                    echo json_encode(["success" => false, "message" => "Failed to reset password"]);
                }
                break;

            default:
                echo json_encode(["success" => false, "message" => "Invalid action"]);
        }
    }
}
