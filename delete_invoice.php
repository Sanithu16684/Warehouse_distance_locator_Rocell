<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, POST');
header('Access-Control-Allow-Headers: Content-Type');

$conn = new mysqli("localhost", "root", "", "location_map");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->id)) {
    echo json_encode(["status" => "error", "message" => "Invalid data or missing ID"]);
    exit();
}

$id = intval($data->id);

$stmt = $conn->prepare("DELETE FROM invoices WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "Invoice deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invoice not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete invoice"]);
}

$stmt->close();
$conn->close();
?>
