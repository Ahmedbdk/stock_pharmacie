<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacie";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier que les données ont été envoyées
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $nb_collaborateurs = $_POST['nb_collaborateurs'] ?? 0;
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $contact = $_POST['contact'] ?? '';
    $date_inscription = date("Y-m-d"); // date du jour
    $date_validite = $_POST['date_validite'] ?? null;

    // Générer un mot de passe aléatoire
    function generatePassword($length = 10) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars)-1)];
        }
        return $password;
    }

    $mot_de_passe = generatePassword();
    $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Préparer et exécuter la requête
    $stmt = $conn->prepare("INSERT INTO pharmacies 
        (nom, adresse, nb_collaborateurs, email, telephone, contact, date_inscription, date_validite, password) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssissssss",
        $nom, $adresse, $nb_collaborateurs, $email, $telephone, $contact, $date_inscription, $date_validite, $mot_de_passe_hash
    );

    if ($stmt->execute()) {
        echo "<h2>Inscription réussie ✅</h2>";
        echo "<p>Mot de passe généré : <strong>$mot_de_passe</strong></p>";
    } else {
        echo "Erreur : " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
