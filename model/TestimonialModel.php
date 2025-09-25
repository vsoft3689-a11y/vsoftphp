<?php
class TestimonialModel
{
    private $conn;
    private $table = "testimonals";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $display_order = (int)$data['display_order'];

        // Step 1: Shift existing reviews with same or higher display_order
        $shiftSql = "UPDATE $this->table 
                 SET display_order = display_order + 1 
                 WHERE display_order >= ?";
        $stmtShift = $this->conn->prepare($shiftSql);
        $stmtShift->bind_param("i", $display_order);
        $stmtShift->execute();

        // Step 2: Insert new review
        $sql = "INSERT INTO $this->table 
            (customer_name, customer_photo_path, designation, rating, review_text, display_order)
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssisi",
            $data['customer_name'],
            $data['customer_photo_path'],
            $data['designation'],
            $data['rating'],
            $data['review_text'],
            $display_order
        );

        return $stmt->execute();
    }


    public function read()
    {
        $result = $this->conn->query("SELECT * FROM $this->table ORDER BY display_order ASC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function update($id, $data)
    {
        $sql = "UPDATE $this->table SET customer_name=?, designation=?, rating=?, review_text=?, display_order=?, is_approved=?, customer_photo_path=IFNULL(?, customer_photo_path) WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssisiisi",
            $data['customer_name'],
            $data['designation'],
            $data['rating'],
            $data['review_text'],
            $data['display_order'],
            $data['is_approved'],
            $data['customer_photo_path'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id)
    {
        $id = intval($id);

        // Step 1: Get the display_order of the review to be deleted
        $result = $this->conn->prepare("SELECT display_order FROM {$this->table} WHERE id=?");
        $result->bind_param("i", $id);
        $result->execute();
        $res = $result->get_result();

        if ($res && $row = $res->fetch_assoc()) {
            $deletedOrder = (int)$row['display_order'];

            // Step 2: Delete the review
            $sql = "DELETE FROM {$this->table} WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            // Step 3: Shift remaining reviews down
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

    public function toggleApproval($id, $newStatus)
    {
        $sql = "UPDATE $this->table SET is_approved=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $newStatus, $id);
        return $stmt->execute();
    }
}
