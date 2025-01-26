<?php
header("Access-Control-Allow-Origin: http://localhost:4200"); // Allow requests from Angular app
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

// Handle GET request (fetch all chansons or a specific chanson by ID)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        // Fetch a specific chanson by ID
        $id = $_GET['id'];
        $sql = "SELECT * FROM chansons WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $chanson = $result->fetch_assoc();
            http_response_code(200);
            echo json_encode($chanson);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Chanson not found"]);
        }
        $stmt->close();
    } else {
        // Fetch all chansons
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
}

// Handle POST request (create a new chanson)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $titre = $data['titre'];
    $artiste = $data['artiste'];
    $album = $data['album'];
    $duree = $data['duree'];
    $genre = $data['genre'] ?? null;
    $annee = $data['annee'] ?? null;
    $image = $data['image'] ?? null;
    $nombreDeLectures = $data['nombreDeLectures'];
    $paroles = $data['paroles'] ?? null;
    $datePublication = $data['datePublication'] ?? null;

    $sql = "INSERT INTO chansons (titre, artiste, album, duree, genre, annee, image, nombreDeLectures, paroles, datePublication) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisssiss", $titre, $artiste, $album, $duree, $genre, $annee, $image, $nombreDeLectures, $paroles, $datePublication);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Chanson created successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error creating chanson: " . $stmt->error]);
    }
    $stmt->close();
}

// Handle PUT request (update a chanson)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $titre = $data['titre'];
    $artiste = $data['artiste'];
    $album = $data['album'];
    $duree = $data['duree'];
    $genre = $data['genre'] ?? null;
    $annee = $data['annee'] ?? null;
    $image = $data['image'] ?? null;
    $nombreDeLectures = $data['nombreDeLectures'];
    $paroles = $data['paroles'] ?? null;
    $datePublication = $data['datePublication'] ?? null;

    $sql = "UPDATE chansons 
            SET titre = ?, artiste = ?, album = ?, duree = ?, genre = ?, annee = ?, image = ?, nombreDeLectures = ?, paroles = ?, datePublication = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisssissi", $titre, $artiste, $album, $duree, $genre, $annee, $image, $nombreDeLectures, $paroles, $datePublication, $id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Chanson updated successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error updating chanson: " . $stmt->error]);
    }
    $stmt->close();
}

// Handle DELETE request (delete a chanson)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];

    $sql = "DELETE FROM chansons WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Chanson deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error deleting chanson: " . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
?>