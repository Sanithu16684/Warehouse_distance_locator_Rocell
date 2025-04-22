<?php
$data = json_decode(file_get_contents("php://input"));
$conn = new mysqli("localhost", "root", "", "location_map");

$name = $conn->real_escape_string($data->name);
$lat = $data->lat;
$lng = $data->lng;

$conn->query("INSERT INTO locations (name, lat, lng) VALUES ('$name', $lat, $lng)");
echo json_encode(["status" => "success"]);
?>
