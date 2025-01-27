<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

// Fetch public lists
$sql = "SELECT * FROM listes order by id desc limit 1";
$result = $conn->query($sql);

$publicLists = [];
while ($row = $result->fetch_assoc()) {
    $publicLists[] = $row;
}

echo json_encode($publicLists);
$conn->close();
?>