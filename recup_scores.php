<?php
$servername = "localhost";
$username ="DBuser9_77";
$password ="12345";
$dbname ="DB9db77";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

$userId = $_POST["userId"];

$otherUserId = null;
$otherUserScore = null;

$sql = "SELECT room_id FROM room_users WHERE id = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $roomId = $row["room_id"];

    $sql = "SELECT id FROM room_users WHERE room_id = $roomId AND id != $userId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $otherUserId = $row["id"];
    }
}

if ($otherUserId !== null) {
    $sql = "SELECT score FROM scores WHERE user_id = $otherUserId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $otherUserScore = $row["score"];
    }
}

$response = [
    "otherUserId" => $otherUserId,
    "otherUserScore" => $otherUserScore
];

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>