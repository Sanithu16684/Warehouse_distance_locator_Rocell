<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, POST');
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

$sql = "UPDATE invoices SET 
        invoice_number = '$invoice_number',
        customer_name = '$customer_name',
        customer_email = '$customer_email',
        customer_phone = '$customer_phone',
        invoice_date = '$invoice_date',
        due_date = '$due_date',
        items = '$items',
        subtotal = $subtotal,
        tax = $tax,
        total = $total,
        status = '$status',
        notes = '$notes'
        WHERE id = $id";

if ($conn->query($sql)) {
    if ($conn->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "Invoice updated successfully"]);
    } else {
        echo json_encode(["status" => "info", "message" => "No changes made or invoice not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update invoice: " . $conn->error]);
}

$conn->close();
?>
