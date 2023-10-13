<?php
$servername = "localhost";
$username ="DBuser9_77";
$password ="12345";
$dbname ="DB9db77";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Récupérez les données du score de l'utilisateur et son ID de la requête POST
$userId = $_POST["userId"];

// Initialisation des variables
$otherUserId = null;
$otherUserScore = null;

// Recherchez la salle de l'utilisateur
$sql = "SELECT room_id FROM room_users WHERE id = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $roomId = $row["room_id"];

    // Maintenant, récupérez l'ID de l'autre utilisateur dans la même salle
    $sql = "SELECT id FROM room_users WHERE room_id = $roomId AND id != $userId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $otherUserId = $row["id"];
    }
}

// Récupérez le score de l'autre utilisateur
if ($otherUserId !== null) {
    $sql = "SELECT score FROM scores WHERE user_id = $otherUserId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $otherUserScore = $row["score"];
    }
}

// Créez un tableau avec les données à renvoyer
$response = [
    "otherUserId" => $otherUserId,
    "otherUserScore" => $otherUserScore
];

// Définissez l'en-tête de réponse pour indiquer que c'est du JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>