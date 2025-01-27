<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mymusic";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["message" => "Connection failed: " . $conn->connect_error]));
}

// Fetch distinct artists
$sql = "SELECT DISTINCT artiste FROM chansons ORDER BY artiste ASC";
$result = $conn->query($sql);

$artists = [];
while ($row = $result->fetch_assoc()) {
    $artists[] = $row['artiste'];
}

echo json_encode($artists);
$conn->close();
?>