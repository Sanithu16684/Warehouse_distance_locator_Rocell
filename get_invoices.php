<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conn = new mysqli("localhost", "root", "", "location_map");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit();
}

$result = $conn->query("SELECT * FROM invoices ORDER BY created_at DESC");

$invoices = [];
while ($row = $result->fetch_assoc()) {
    $invoices[] = $row;
}

echo json_encode($invoices);
$conn->close();
?>
