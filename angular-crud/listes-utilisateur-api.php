<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['utilisateur_id'])) {
    $utilisateur_id = $_GET['utilisateur_id'];

    $sql = "SELECT l.* FROM listes l
            INNER JOIN liste_utilisateur lu ON l.id = lu.liste_id
            WHERE lu.utilisateur_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $utilisateur_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $listes = [];
    while ($row = $result->fetch_assoc()) {
        $listes[] = $row;
    }

    echo json_encode($listes);
    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing utilisateur_id parameter"]);
}

$conn->close();
?>