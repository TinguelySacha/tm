<?php
$servername = "localhost";
$username ="DBuser9_77";
$password ="12345";
$dbname ="DB9db77";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}


$score = $_POST["points1"];
$userId = $_POST["userId"];


$sql = "INSERT INTO scores (user_id, score) VALUES ($userId, $score)";
if ($conn->query($sql) === TRUE) {
    $response = [
        "success" => true,
        "message" => "Score enregistré avec succès."
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