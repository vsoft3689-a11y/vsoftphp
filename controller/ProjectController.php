<?php
header("Content-Type: application/json");

include __DIR__ . '/../model/ProjectModel.php';
include __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$controller = new ProjectController();
$controller->handleRequest($action);

class ProjectController
{
    private $db;
    private $projectModel;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->projectModel = new ProjectModel($this->db);
    }

    public function handleRequest($action)
    {
        switch ($action) {
            case "create":
                $data = $_POST;
                $data['file_path_abstract'] = $this->uploadFile('abstract');
                $data['file_path_basepaper'] = $this->uploadFile('basepaper');
                echo json_encode(["success" => $this->projectModel->create($data)]);
                break;

            case "read":
                echo json_encode($this->projectModel->read());
                break;

            case "getById":
                $id = $_GET['id'];
                echo json_encode($this->projectModel->getById($id));
                break;

            case "getByFilters":
                $degree = $_POST['degree'] ?? '';
                $branch = $_POST['branch'] ?? '';
                $type   = $_POST['type'] ?? '';
                $domain = $_POST['domain'] ?? '';

                echo json_encode($this->projectModel->getByFilters($degree, $branch, $type, $domain));
                break;



            case "update":
                $id = $_POST['id'];
                $data = $_POST;

                // Fetch existing file paths from DB
                $project = $this->projectModel->getById($id); // you need a method to get project by id

                // Handle Abstract Paper
                if (!empty($_FILES['abstract']['name']) && $_FILES['abstract']['error'] === 0) {
                    $data['file_path_abstract'] = $this->uploadFile('abstract'); // new file uploaded
                } else {
                    $data['file_path_abstract'] = $project['file_path_abstract']; // keep existing
                }

                // Handle Basepaper
                if (!empty($_FILES['basepaper']['name']) && $_FILES['basepaper']['error'] === 0) {
                    $data['file_path_basepaper'] = $this->uploadFile('basepaper'); // new file uploaded
                } else {
                    $data['file_path_basepaper'] = $project['file_path_basepaper']; // keep existing
                }

                echo json_encode(["success" => $this->projectModel->update($id, $data)]);
                break;


            case "delete":
                $id = $_POST['id'];
                echo json_encode(["success" => $this->projectModel->delete($id)]);
                break;

            default:
                echo json_encode(["error" => "Invalid action"]);
                break;
        }
    }

    private function uploadFile($fieldName, $oldPath = null)
    {
        $uploadDir = "../uploads/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (!empty($_FILES[$fieldName]['name'])) {
            $filePath = $uploadDir . time() . "_" . basename($_FILES[$fieldName]['name']);
            move_uploaded_file($_FILES[$fieldName]['tmp_name'], $filePath);
            return $filePath;
        }
        return $oldPath;
    }
}
