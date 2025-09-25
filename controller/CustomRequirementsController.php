<?php
header("Content-Type: application/json");

include_once __DIR__ . "/../config/Database.php";
include_once __DIR__ . "/../model/CustomRequirementsModel.php";

$action = $_REQUEST['action'] ?? '';

$controller = new CustomRequirementsController();
$controller->handleRequest($action);

class CustomRequirementsController
{
    private $db;
    private $customModel;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        if (!$this->db) {
            echo json_encode(["status" => "error", "message" => "Database connection failed"]);
            exit;
        }
        $this->customModel = new CustomRequirementsModel($this->db);
    }

    public function handleRequest($action)
    {
        switch ($action) {
            case "create":
                $this->create();
                break;
            case "read":
                $this->readAll();
                break;
            case "read_single":
                $this->readSingle();
                break;
            case "update":
                $this->update();
                break;
            case "delete":
                $this->delete();
                break;
            case "update_status":
                $this->updateStatus();
                break;
            default:
                echo json_encode(["status" => "error", "message" => "Invalid action"]);
        }
    }

    private function create()
    {
        if (empty($_POST['user_id']) || empty($_POST['title'])) {
            echo json_encode(["status" => "error", "message" => "user_id and title are required"]);
            return;
        }

        $data = [
            "user_id" => intval($_POST['user_id']),
            "title" => $_POST['title'],
            "description" => $_POST['description'] ?? null,
            "technologies" => $_POST['technologies'] ?? null,
            "status" => $_POST['status'] ?? 'pending',
            "document_path" => null
        ];

        if (!empty($_FILES['document']['name'])) {
            $targetDir = __DIR__ . "/../uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $fileName = time() . "_" . basename($_FILES['document']['name']);
            $targetFile = $targetDir . $fileName;
            if (!move_uploaded_file($_FILES['document']['tmp_name'], $targetFile)) {
                echo json_encode(["status" => "error", "message" => "File upload failed"]);
                return;
            }
            $data['document_path'] = "uploads/" . $fileName;
        }

        $res = $this->customModel->create($data);
        if ($res) {
            echo json_encode(["status" => "success", "message" => "Project created"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to create project"]);
        }
    }

    private function readAll()
    {
        $rows = $this->customModel->readAll();
        echo json_encode($rows);
    }

    private function readSingle()
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid ID"]);
            return;
        }
        $row = $this->customModel->read($id);
        if ($row) {
            echo json_encode($row);
        } else {
            echo json_encode(["status" => "error", "message" => "Project not found"]);
        }
    }

    private function update()
    {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid ID"]);
            return;
        }
        if (empty($_POST['title'])) {
            echo json_encode(["status" => "error", "message" => "Title required"]);
            return;
        }
        $data = [
            "title" => $_POST['title'],
            "description" => $_POST['description'] ?? null,
            "technologies" => $_POST['technologies'] ?? null,
            "status" => $_POST['status'] ?? 'pending',
            "document_path" => $_POST['document_path'] ?? null
        ];

        if (!empty($_FILES['document']['name'])) {
            $targetDir = __DIR__ . "/../uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $fileName = time() . "_" . basename($_FILES['document']['name']);
            $targetFile = $targetDir . $fileName;
            if (!move_uploaded_file($_FILES['document']['tmp_name'], $targetFile)) {
                echo json_encode(["status" => "error", "message" => "File upload failed"]);
                return;
            }
            $data['document_path'] = "uploads/" . $fileName;
        }

        $res = $this->customModel->update($id, $data);
        if ($res) {
            echo json_encode(["status" => "success", "message" => "Project updated"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update project"]);
        }
    }

    private function delete()
    {
        $id = 0;
        if (!empty($_POST['id'])) {
            $id = intval($_POST['id']);
        } elseif (!empty($_GET['id'])) {
            $id = intval($_GET['id']);
        }

        if ($id <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid ID"]);
            return;
        }

        $res = $this->customModel->delete($id);
        if ($res) {
            echo json_encode(["status" => "success", "message" => "Project deleted"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete project"]);
        }
    }

    private function updateStatus()
    {
        $id = intval($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? null;
        if ($id <= 0 || !$status) {
            echo json_encode(["status" => "error", "message" => "Invalid ID or status"]);
            return;
        }
        $res = $this->customModel->updateStatus($id, $status);
        if ($res) {
            echo json_encode(["status" => "success", "message" => "Status updated"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update status"]);
        }
    }
}
