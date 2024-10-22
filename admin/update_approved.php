<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['id'] ?? null;
    $approved = $_POST['approved'] ?? null;

    if ($admin_id === null || $approved === null) {
        echo json_encode(["success" => false, "message" => "Invalid data provided."]);
        exit();
    }

    try {
        $db = new PDO('sqlite:admin_signup.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE admin_signup SET approved = :approved WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':approved', $approved, PDO::PARAM_INT);
        $stmt->bindParam(':id', $admin_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Approved status updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update approved status."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Datubāzes kļūda: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
