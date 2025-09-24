<?php
// register.php

// Connexion à la base de données
$servername = "localhost";
$username = "root";       // par défaut XAMPP
$password = "";           // par défaut XAMPP
$dbname = "pharmacie";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier que les données ont été envoyées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['mdp'];

    // Hasher le mot de passe
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Préparer la requête
    $stmt = $conn->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nom, $email, $passwordHash);

    if ($stmt->execute()) {
        echo "Utilisateur créé avec succès ✅";
    } else {
        echo "Erreur : " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
