<?php
$servername = "localhost";
$username ="DBuser9_77";
$password ="12345";
$dbname ="DB9db77";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

$ball_name = $_POST["ball_name"];
$ball_vx = $_POST["ball_vx"];
$ball_vy = $_POST["ball_vy"];
$playerid = $_POST["userid"];

$sql = "INSERT INTO player_data (ball_name, ball_vx, ball_vy, player_id) VALUES ($ball_name, $ball_vx, $ball_vy, $playerid)";
if ($conn->query($sql) === TRUE) {
    $response = [
        "success" => true,
        "message" => "Tir enregistré avec succès."
    ];
} else {
    $response = [
        "success" => false,
        "message" => "Erreur MySQL : " . mysqli_error($conn)
    ];
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>