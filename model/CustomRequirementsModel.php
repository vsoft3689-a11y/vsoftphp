<?php
// path: model/CustomRequirementsModel.php

class CustomRequirementsModel {
    private $conn;
    private $table = "custom_requirements";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (user_id, title, description, technologies, status, document_path) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            error_log("Prepare failed in create: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param(
            "isssss",
            $data['user_id'],
            $data['title'],
            $data['description'],
            $data['technologies'],
            $data['status'],
            $data['document_path']
        );
        $res = $stmt->execute();
        if (!$res) {
            error_log("Execute failed in create: " . $stmt->error);
        }
        $stmt->close();
        return $res;
    }

    public function readAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        if (!$result) {
            error_log("Query failed in readAll: " . $this->conn->error);
            return [];
        }
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $result->free();
        return $rows;
    }

    public function read($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        if (!$stmt) {
            error_log("Prepare failed in read: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET title = ?, description = ?, technologies = ?, status = ?, document_path = ?
            WHERE id = ?
        ");
        if (!$stmt) {
            error_log("Prepare failed in update: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param(
            "sssssi",
            $data['title'],
            $data['description'],
            $data['technologies'],
            $data['status'],
            $data['document_path'],
            $id
        );
        $res = $stmt->execute();
        if (!$res) {
            error_log("Execute failed in update: " . $stmt->error);
        }
        $stmt->close();
        return $res;
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        if (!$stmt) {
            error_log("Prepare failed in delete: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        if (!$res) {
            error_log("Execute failed in delete: " . $stmt->error);
        }
        $stmt->close();
        return $res;
    }

    // Optional: method to update only status
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET status = ?
            WHERE id = ?
        ");
        if (!$stmt) {
            error_log("Prepare failed in updateStatus: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("si", $status, $id);
        $res = $stmt->execute();
        if (!$res) {
            error_log("Execute failed in updateStatus: " . $stmt->error);
        }
        $stmt->close();
        return $res;
    }
}