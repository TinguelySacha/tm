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
$otherid = $_POST["otherid"];
$ball_name = null;
$ball_vx = null;
$ball_vy = null;

// Récupérez le score de l'autre utilisateur
$sql = "SELECT ball_name, ball_vx, ball_vy FROM player_data WHERE player_id = $otherid";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ball_name = intval($row["ball_name"]); // Convertissez en entier
    $ball_vx = floatval($row["ball_vx"]); // Convertissez en nombre à virgule flottante (float)
    $ball_vy = floatval($row["ball_vy"]); // Convertissez en nombre à virgule flottante (float)
    
    // Supprimer la ligne du serveur après avoir récupéré les données
    $delete_sql = "DELETE FROM player_data WHERE player_id = $otherid";
    if ($conn->query($delete_sql) !== TRUE) {
        echo "Erreur lors de la suppression de la ligne : " . $conn->error;
        exit; // Quittez le script en cas d'erreur.
    }
}

// Créez un tableau avec les données à renvoyer
$response = [
    "ball_name" => $ball_name,
    "ball_vx" => $ball_vx,
    "ball_vy" => $ball_vy
];

// Définissez l'en-tête de réponse pour indiquer que c'est du JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>