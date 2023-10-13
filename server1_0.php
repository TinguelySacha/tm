<?php
$servername = "localhost";
$username ="DBuser9_77";
$password ="12345";
$dbname ="DB9db77";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Récupérez le nom d'utilisateur de la requête AJAX
$username = $_POST["username"];
// Recherchez ou créez une "room" (implémentez la logique appropriée ici)
$sql = "SELECT r.id FROM rooms r
        LEFT JOIN room_users ru ON r.id = ru.room_id
        GROUP BY r.id
        HAVING COUNT(ru.id) < 2
        LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Une "room" existante a été trouvée, utilisez son ID
    $row = $result->fetch_assoc();
    $roomId = $row["id"];

} else {
    // Créez un nom de room unique
    $newRoomName = "Room" . uniqid();

    // Aucune "room" existante n'a été trouvée, créez-en une nouvelle avec le nom unique
    $sql = "INSERT INTO rooms (room_name) VALUES ('$newRoomName')";
    if ($conn->query($sql) === TRUE) {
        $roomId = $conn->insert_id; // Récupérez l'ID de la nouvelle "room"
    } else {
        // Gestion des erreurs lors de la création de la "room"
        $response = [
            "success" => false,
            "message" => "Erreur MySQL : " . mysqli_error($conn)
        ];
        echo json_encode($response);
        exit;
    }
}

// Assurez-vous que chaque room a au plus 2 utilisateurs
$userCount = 0;

// Vérifiez combien d'utilisateurs sont déjà dans la salle
$sql = "SELECT COUNT(*) AS user_count FROM room_users WHERE room_id = $roomId";
$userCountResult = $conn->query($sql);
if ($userCountResult) {
    $userCountRow = $userCountResult->fetch_assoc();
    $userCount = $userCountRow["user_count"];
}

if ($userCount < 2) {
    // L'utilisateur peut rejoindre la room
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
        // Gestion des erreurs lors de l'ajout de l'utilisateur à la room
        $response = [
            "success" => false,
            "message" => "Erreur MySQL : " . mysqli_error($conn)
        ];
    }
} else {
    // La room est pleine, l'utilisateur ne peut pas rejoindre
    $response = [
        "success" => false,
        "message" => "La room est pleine. Vous ne pouvez pas rejoindre cette room."
    ];
}

if ($userCount == 1) {
    // Mettez à jour game_ready à 1
    $sql = "UPDATE rooms SET game_ready = 1 WHERE id = $roomId";
    if ($conn->query($sql) === TRUE) {
        // La room est maintenant prête, vous pouvez renvoyer la réponse appropriée
        $response = [
            "success" => true,
            "gameReady" => true,
            "redirect_url" => "online1_1.php",
            "id" => $userId
        ];
    } else {
        // Gestion des erreurs lors de la mise à jour de game_ready
        $response = [
            "success" => false,
            "message" => "Erreur MySQL lors de la mise à jour de game_ready : " . mysqli_error($conn)
        ];
    }
};

// Définissez l'en-tête de réponse pour indiquer que c'est du JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>