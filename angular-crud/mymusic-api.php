<?php
// Allow requests from any origin (for development only)
header("Access-Control-Allow-Origin: *");

// Allow specific HTTP methods (e.g., GET, POST, PUT, DELETE, OPTIONS)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow specific headers (e.g., Content-Type, Authorization)
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Respond with a 200 OK status for preflight requests
    http_response_code(200);
    exit();
}
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "mymusic";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM chansons WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $chanson = $result->fetch_assoc();
            echo json_encode($chanson);
        } else {
            $result = $conn->query("SELECT * FROM chansons");
            $chansons = [];
            while ($row = $result->fetch_assoc()) {
                $chansons[] = $row;
            }
            echo json_encode($chansons);
        }
        break;

    case 'POST':
        // Log the raw input for debugging
        $rawInput = file_get_contents("php://input");
        error_log("Raw input: " . $rawInput);

        // Check if the raw input is empty
        if (empty($rawInput)) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Empty request body", "rawInput" => $rawInput]);
            exit;
        }

        // Decode the JSON data
        $data = json_decode($rawInput, true);

        // Check if the data is valid
        if ($data === null) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Invalid JSON data", "rawInput" => $rawInput]);
            exit;
        }


        // Validate required fields
        $requiredFields = ['titre', 'artiste', 'album', 'duree', 'nombreDeLectures'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                http_response_code(400); // Bad Request
                echo json_encode(["error" => "Missing required field: $field"]);
                exit;
            }
        }

        $titre = $data['titre'];
        $artiste = $data['artiste'];
        $album = $data['album'];
        $paroles = $data['paroles'];
        $datePublication = $data['datePublication'];
        $duree = $data['duree'];
        $nombreDeLectures = $data['nombreDeLectures'];

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO `chansons` (`titre`, `artiste`, `paroles`, `album`, `datePublication`, `duree`, `nombreDeLectures`) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["error" => "Failed to prepare SQL statement: " . $conn->error]);
            exit;
        }

        // Bind parameters and execute
        $stmt->bind_param("sssssii", $titre, $artiste, $paroles, $album, $datePublication, $duree, $nombreDeLectures);
        if (!$stmt->execute()) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["error" => "Failed to execute SQL statement: " . $stmt->error]);
            exit;
        }
        echo json_encode(["message" => "Chanson created successfully"]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];
        $titre = $data['titre'];
        $artiste = $data['artiste'];
        $album = $data['album'];
        $duree = $data['duree'];
        $nombreDeLectures = $data['nombreDeLectures'];

        $stmt = $conn->prepare("UPDATE chansons SET titre = ?, artiste = ?, album = ?, duree = ?, nombreDeLectures = ? WHERE id = ?");
        $stmt->bind_param("sssiii", $titre, $artiste, $album, $duree, $nombreDeLectures, $id);
        $stmt->execute();
        echo json_encode(["message" => "Chanson updated successfully"]);
        break;

    case 'DELETE':
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM chansons WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["message" => "Chanson deleted successfully"]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}

$conn->close();
?>