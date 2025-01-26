<?php

header("Access-Control-Allow-Origin: *"); // Allow requests from Angular app
header("Content-Type: application/json; charset=UTF-8"); // Set response type to JSON
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Allow these methods
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight request (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "mymusic"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["message" => "Connection failed: " . $conn->connect_error]));
}

// Handle GET request (fetch all chansons)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM chansons";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $chansons = [];
        while ($row = $result->fetch_assoc()) {
            $chansons[] = $row;
        }
        http_response_code(200);
        echo json_encode($chansons);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "No chansons found"]);
    }
}

$conn->close();
?>