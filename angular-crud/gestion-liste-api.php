<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin:*"); // Allow requests from Angular app
header("Content-Type: application/json; charset=UTF-8"); // Set response type to JSON
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Allow these methods
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

// Validate visibilite field
function validateVisibilite($visibilite)
{
    return in_array($visibilite, ['publique', 'prive']);
}

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["message" => "Connection failed: " . $conn->connect_error]));
}

// Handle GET request (fetch all lists or search by titre)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the request is for fetching chansons of a specific list
    if (isset($_GET['liste_id'])) {
        $liste_id = $_GET['liste_id'];
        $sql = "SELECT c.* FROM chansons c
        INNER JOIN listes_chansons lc ON c.id = lc.chanson_id
        WHERE lc.liste_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $liste_id);
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                $chansons = [];
                while ($row = $result->fetch_assoc()) {
                    $chansons[] = $row;
                }

                http_response_code(200);
                echo json_encode($chansons);

            } else {
                http_response_code(500);
                echo json_encode(["message" => "Failed to execute query: " . $stmt->error]);
            }
            $stmt->close();
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to prepare statement: " . $conn->error]);
        }
    } else {
        // Fetch all lists or search by titre
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        if ($search) {
            // Filter by titre (case-sensitive)
            $sql = "SELECT * FROM listes WHERE titre LIKE ?";
            $stmt = $conn->prepare($sql);
            $searchTerm = "%" . $search . "%";
            $stmt->bind_param("s", $searchTerm);
        } else {
            // Fetch all lists
            $sql = "SELECT * FROM listes";
            $stmt = $conn->prepare($sql);
        }

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
            echo json_encode(["message" => "No lists found"]);
        }
        $stmt->close();
    }
}

// Handle POST request (create a new list or add a chanson to a list)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the request is for adding a chanson to a list
    if (isset($_GET['liste_id'])) {
        $liste_id = $_GET['liste_id'];
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate chanson_id
        if (!isset($data['chanson_id']) || !is_numeric($data['chanson_id'])) {
            http_response_code(400);
            echo json_encode(["message" => "Invalid or missing 'chanson_id' in request body"]);
            exit();
        }

        $chanson_id = $data['chanson_id'];

        // Prepare the SQL statement
        $sql = "INSERT INTO listes_chansons (liste_id, chanson_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ii", $liste_id, $chanson_id);
            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(["message" => "Chanson added to list successfully"]);
            } else {
                // Log the SQL error
                error_log("SQL Error: " . $stmt->error);
                http_response_code(500);
                echo json_encode(["message" => "Error adding chanson to list: " . $stmt->error]);
            }
            $stmt->close();
        } else {
            // Log the statement preparation error
            error_log("Failed to prepare statement: " . $conn->error);
            http_response_code(500);
            echo json_encode(["message" => "Failed to prepare statement: " . $conn->error]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Missing 'liste_id' parameter"]);
    }
}

// Handle PUT request (update a list)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    // Validate visibilite
    if (!validateVisibilite($data['visibilite'])) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid value for visibilite. Allowed values are 'publique' or 'prive'."]);
        exit();
    }
    $id = $data['id'];
    $titre = $data['titre'];
    $soustitre = $data['soustitre'];
    $image = $data['image'];
    $description = $data['description'];
    $type = $data['type'];
    $verifie = $data['verifie'];
    $datePublication = $data['datePublication'];
    $visibilite = $data['visibilite'];

    $sql = "UPDATE listes 
            SET titre = ?, soustitre = ?, image = ?, description = ?, type = ?, verifie = ?, datePublication = ?, visibilite = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssissi", $titre, $soustitre, $image, $description, $type, $verifie, $datePublication, $visibilite, $id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "List updated successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error updating list: " . $stmt->error]);
    }
    $stmt->close();
}

// Handle DELETE request (delete a list or remove a chanson from a list)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if the request is for removing a chanson from a list
    if (isset($_GET['liste_id']) && isset($_GET['chanson_id'])) {
        $liste_id = $_GET['liste_id'];
        $chanson_id = $_GET['chanson_id'];

        $sql = "DELETE FROM listes_chansons WHERE liste_id = ? AND chanson_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $liste_id, $chanson_id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "Chanson removed from list successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error removing chanson from list: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        // Delete a list
        $id = $_GET['id'];

        $sql = "DELETE FROM listes WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "List deleted successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error deleting list: " . $stmt->error]);
        }
        $stmt->close();
    }
}

$conn->close();
?>