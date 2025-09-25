<?php
class TeamMemberModel
{
    private $conn;
    private $table = "team_members";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data, $file)
    {
        $profile_picture_path = null;

        // Handle file upload
        if (isset($file['profile_picture']) && $file['profile_picture']['error'] == 0) {
            $uploadDir = __DIR__ . "/../uploads/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = time() . "_" . basename($file['profile_picture']['name']);
            $target = $uploadDir . $filename;
            if (move_uploaded_file($file['profile_picture']['tmp_name'], $target)) {
                $profile_picture_path = "uploads/" . $filename;
            }
        }

        $display_order = (int)$data['display_order'];

        // Step 1: Shift existing records with same or higher display_order
        $shiftSql = "UPDATE {$this->table} SET display_order = display_order + 1 WHERE display_order >= ?";
        $stmtShift = $this->conn->prepare($shiftSql);
        $stmtShift->bind_param("i", $display_order);
        $stmtShift->execute();

        // Step 2: Insert new record
        $sql = "INSERT INTO {$this->table} 
        (name, designation, bio, profile_picture_path, social_facebook, social_twitter, social_linkedin, display_order) 
        VALUES (?,?,?,?,?,?,?,?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssssssi",
            $data['name'],
            $data['designation'],
            $data['bio'],
            $profile_picture_path,
            $data['social_facebook'],
            $data['social_twitter'],
            $data['social_linkedin'],
            $display_order
        );

        return $stmt->execute();
    }

    public function read()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY display_order ASC";
        $result = $this->conn->query($sql);
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function update($data, $file)
    {
        $id = (int)$data['id'];
        $new_order = (int)$data['display_order'];

        // Step 1: Fetch current record
        $current = $this->conn->query("SELECT profile_picture_path, display_order FROM {$this->table} WHERE id=$id")->fetch_assoc();
        $oldImage = $current['profile_picture_path'];
        $old_order = (int)$current['display_order'];

        // Step 2: Shift display orders if new order changed
        if ($new_order !== $old_order) {
            if ($new_order < $old_order) {
                // Move up: increment display_order for records between new_order and old_order -1
                $shiftSql = "UPDATE {$this->table} 
                         SET display_order = display_order + 1 
                         WHERE display_order >= ? AND display_order < ? AND id != ?";
                $stmtShift = $this->conn->prepare($shiftSql);
                $stmtShift->bind_param("iii", $new_order, $old_order, $id);
                $stmtShift->execute();
            } else {
                // Move down: decrement display_order for records between old_order +1 and new_order
                $shiftSql = "UPDATE {$this->table} 
                         SET display_order = display_order - 1 
                         WHERE display_order <= ? AND display_order > ? AND id != ?";
                $stmtShift = $this->conn->prepare($shiftSql);
                $stmtShift->bind_param("iii", $new_order, $old_order, $id);
                $stmtShift->execute();
            }
        }

        $profile_picture_sql = "";

        // Step 3: Handle new file upload
        if (isset($file['profile_picture']) && $file['profile_picture']['error'] == 0) {
            $uploadDir = __DIR__ . "/../uploads/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $filename = time() . "_" . basename($file['profile_picture']['name']);
            $target = $uploadDir . $filename;

            if (move_uploaded_file($file['profile_picture']['tmp_name'], $target)) {
                $profile_picture_path = "uploads/" . $filename;
                $profile_picture_sql = ", profile_picture_path='" . $this->conn->real_escape_string($profile_picture_path) . "'";

                // Delete old image if exists
                if (!empty($oldImage) && file_exists(__DIR__ . "/../" . $oldImage)) {
                    unlink(__DIR__ . "/../" . $oldImage);
                }
            }
        }

        // Step 4: Update query
        $sql = "UPDATE {$this->table} SET 
            name='" . $this->conn->real_escape_string($data['name']) . "', 
            designation='" . $this->conn->real_escape_string($data['designation']) . "', 
            bio='" . $this->conn->real_escape_string($data['bio']) . "', 
            social_facebook='" . $this->conn->real_escape_string($data['social_facebook']) . "', 
            social_twitter='" . $this->conn->real_escape_string($data['social_twitter']) . "', 
            social_linkedin='" . $this->conn->real_escape_string($data['social_linkedin']) . "', 
            display_order=$new_order
            $profile_picture_sql
        WHERE id=$id";

        return $this->conn->query($sql);
    }

    public function delete($id)
    {
        $id = intval($id);

        // Step 1: Fetch old image path and display_order
        $result = $this->conn->prepare("SELECT profile_picture_path, display_order FROM {$this->table} WHERE id=?");
        $result->bind_param("i", $id);
        $result->execute();
        $res = $result->get_result();

        if ($res && $row = $res->fetch_assoc()) {
            $oldImage = $row['profile_picture_path'];
            $deletedOrder = (int)$row['display_order'];

            // Step 2: Delete file if it exists
            if (!empty($oldImage) && file_exists(__DIR__ . "/../" . $oldImage)) {
                unlink(__DIR__ . "/../" . $oldImage);
            }

            // Step 3: Delete database row
            $delStmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
            $delStmt->bind_param("i", $id);
            $delStmt->execute();

            // Step 4: Shift display_order of remaining records down
            $shiftSql = "UPDATE {$this->table} 
                     SET display_order = display_order - 1 
                     WHERE display_order > ?";
            $stmtShift = $this->conn->prepare($shiftSql);
            $stmtShift->bind_param("i", $deletedOrder);
            $stmtShift->execute();

            return true;
        }

        return false;
    }

    public function toggleStatus($id, $is_active)
    {
        return $this->conn->query("UPDATE {$this->table} SET is_active=$is_active WHERE id=$id");
    }
}
