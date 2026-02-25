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

// Validate required fields
if (empty($data->invoice_number) || empty($data->customer_name) || 
    empty($data->invoice_date) || empty($data->due_date) || 
    empty($data->status) || !isset($data->items)) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit();
}

$id = intval($data->id);
$invoice_number = $data->invoice_number;
$customer_name = $data->customer_name;
$customer_email = isset($data->customer_email) ? $data->customer_email : '';
$customer_phone = isset($data->customer_phone) ? $data->customer_phone : '';
$invoice_date = $data->invoice_date;
$due_date = $data->due_date;
$items = json_encode($data->items);
$subtotal = floatval($data->subtotal);
$tax = floatval($data->tax);
$total = floatval($data->total);
$status = $data->status;
$notes = isset($data->notes) ? $data->notes : '';

$stmt = $conn->prepare("UPDATE invoices SET invoice_number = ?, customer_name = ?, customer_email = ?, customer_phone = ?, invoice_date = ?, due_date = ?, items = ?, subtotal = ?, tax = ?, total = ?, status = ?, notes = ? WHERE id = ?");
$stmt->bind_param("sssssssdddssi", $invoice_number, $customer_name, $customer_email, $customer_phone, $invoice_date, $due_date, $items, $subtotal, $tax, $total, $status, $notes, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "Invoice updated successfully"]);
    } else {
        echo json_encode(["status" => "info", "message" => "No changes made or invoice not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update invoice: " . $stmt->error]);
}

$stmt->close();

$conn->close();
?>
