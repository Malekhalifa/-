<?php
header("Access-Control-Allow-Origin: *"); // Allow requests from Angular app
header("Content-Type: application/json; charset=UTF-8"); // Set response type to JSON
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

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

// Get the 'visibilite' parameter from the query string
$id = isset($_GET['liste_id']) ? $_GET['liste_id'] : null;

if ($id) {
    // Fetch lists based on 'id'
    $sql = "SELECT * FROM listes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $listes = [];
        while ($row = $result->fetch_assoc()) {
            $listes[] = $row;
        }
        http_response_code(200);
        echo json_encode($listes);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "No lists found with id = " . $id]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing 'id' parameter"]);
}

$stmt->close();
$conn->close();
?>