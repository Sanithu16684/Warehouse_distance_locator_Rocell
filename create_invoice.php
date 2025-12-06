<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$conn = new mysqli("localhost", "root", "", "location_map");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit();
}

$invoice_number = $conn->real_escape_string($data->invoice_number);
$customer_name = $conn->real_escape_string($data->customer_name);
$customer_email = $conn->real_escape_string($data->customer_email);
$customer_phone = $conn->real_escape_string($data->customer_phone);
$invoice_date = $conn->real_escape_string($data->invoice_date);
$due_date = $conn->real_escape_string($data->due_date);
$items = $conn->real_escape_string(json_encode($data->items));
$subtotal = floatval($data->subtotal);
$tax = floatval($data->tax);
$total = floatval($data->total);
$status = $conn->real_escape_string($data->status);
$notes = isset($data->notes) ? $conn->real_escape_string($data->notes) : '';

$sql = "INSERT INTO invoices (invoice_number, customer_name, customer_email, customer_phone, invoice_date, due_date, items, subtotal, tax, total, status, notes) 
        VALUES ('$invoice_number', '$customer_name', '$customer_email', '$customer_phone', '$invoice_date', '$due_date', '$items', $subtotal, $tax, $total, '$status', '$notes')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "Invoice created successfully", "id" => $conn->insert_id]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to create invoice: " . $conn->error]);
}

$conn->close();
?>
