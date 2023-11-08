<?php
$servername = "localhost";
$username ="DBuser9_77";
$password ="12345";
$dbname ="DB9db77";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

$username = $_POST["username"];


$sql = "SELECT r.id FROM rooms r
        LEFT JOIN room_users ru ON r.id = ru.room_id
        GROUP BY r.id
        HAVING COUNT(ru.id) < 2
        LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $roomId = $row["id"];

} else {
    $newRoomName = "Room" . uniqid();

    $sql = "INSERT INTO rooms (room_name) VALUES ('$newRoomName')";
    if ($conn->query($sql) === TRUE) {
        $roomId = $conn->insert_id; 
    } else {
        $response = [
            "success" => false,
            "message" => "Erreur MySQL : " . mysqli_error($conn)
        ];
        echo json_encode($response);
        exit;
    }
}

$userCount = 0;

$sql = "SELECT COUNT(*) AS user_count FROM room_users WHERE room_id = $roomId";
$userCountResult = $conn->query($sql);
if ($userCountResult) {
    $userCountRow = $userCountResult->fetch_assoc();
    $userCount = $userCountRow["user_count"];
}

if ($userCount < 2) {
    $sql = "INSERT INTO room_users (room_id, username) VALUES ($roomId, '$username')";
    if ($conn->query($sql) === TRUE) {
        $userId = $conn->insert_id;
        $response = [
            "success" => true,
            "gameReady" => false,
            "roomId" => $roomId,
            "id" => $userId
        ];
    } else {
        $response = [
            "success" => false,
            "message" => "Erreur MySQL : " . mysqli_error($conn)
        ];
    }
} else {
    $response = [
        "success" => false,
        "message" => "La room est pleine. Vous ne pouvez pas rejoindre cette room."
    ];
}

if ($userCount == 1) {
    $sql = "UPDATE rooms SET game_ready = 1 WHERE id = $roomId";
    if ($conn->query($sql) === TRUE) {
        $response = [
            "success" => true,
            "gameReady" => true,
            "redirect_url" => "online1_1.php",
            "id" => $userId
        ];
    } else {
        $response = [
            "success" => false,
            "message" => "Erreur MySQL lors de la mise à jour de game_ready : " . mysqli_error($conn)
        ];
    }
};

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>