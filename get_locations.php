<?php
$conn = new mysqli("localhost", "root", "", "location_map");
$result = $conn->query("SELECT * FROM locations");

$locations = [];
while ($row = $result->fetch_assoc()) {
  $locations[] = $row;
}
header('Content-Type: application/json');
echo json_encode($locations);
?>
