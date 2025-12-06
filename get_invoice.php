<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conn = new mysqli("localhost", "root", "", "location_map");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid invoice ID"]);
    exit();
}

$stmt = $conn->prepare("SELECT * FROM invoices WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $invoice = $result->fetch_assoc();
    echo json_encode($invoice);
} else {
    echo json_encode(["status" => "error", "message" => "Invoice not found"]);
}

$stmt->close();
$conn->close();
?>
