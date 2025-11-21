<?php
session_start();

// 🔒 Empêcher l'accès si NON connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connexion BDD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacie";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// 🔥 Récupérer les catégories SANS DOUBLONS
$sql = "SELECT DISTINCT categorie FROM produits";
$result = $conn->query($sql);
?>
