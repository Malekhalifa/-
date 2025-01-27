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

// Validate artist parameter
$artist = isset($_GET['artist']) ? $_GET['artist'] : null;

if (!$artist) {
    http_response_code(400);
    die(json_encode(["message" => "Artist parameter is required"]));
}

// Fetch chansons by artist
$sql = "SELECT * FROM chansons WHERE artiste = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $artist);
$stmt->execute();
$result = $stmt->get_result();

$chansons = [];
while ($row = $result->fetch_assoc()) {
    $chansons[] = $row;
}

echo json_encode($chansons);
$conn->close();
?>