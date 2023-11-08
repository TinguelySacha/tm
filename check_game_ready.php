<?php
$servername = "localhost";
$username ="DBuser9_77";
$password ="12345";
$dbname ="DB9db77";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}
$roomId = $_GET["roomId"];

$sql = "SELECT game_ready FROM rooms WHERE id = $roomId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $gameReady = (bool)$row["game_ready"];

    $response = [
        "success" => true,
        "gameReady" => $gameReady,
        "redirect_url" => $gameReady ? "online1_1.php" : ""
    ];
} else {
    $response = [
        "success" => false,
        "message" => "Room non trouvée."
    ];
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>