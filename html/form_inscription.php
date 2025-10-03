<?php
// Démarrer la session (utile plus tard si besoin)
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacie";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion à la BDD : " . $conn->connect_error);
}

// Vérifier que le formulaire a été envoyé
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer et nettoyer les données
    $nom = trim($_POST['nom']);
    $adresse = trim($_POST['adresse']);
    $nb_collaborateurs = !empty($_POST['nb_collaborateurs']) ? (int)$_POST['nb_collaborateurs'] : 0;
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $contact = trim($_POST['contact'] ?? '');
    $date_inscription = date("Y-m-d");
    $date_validite = !empty($_POST['date_validite']) ? $_POST['date_validite'] : null;

    // Vérifier si l'email existe déjà
    $email_check = $conn->prepare("SELECT id FROM pharmacies WHERE email = ?");
    $email_check->bind_param("s", $email);
    $email_check->execute();
    $email_check_result = $email_check->get_result();

    if ($email_check_result->num_rows > 0) {
        die("<p style='color:red;'>Erreur : cet email est déjà utilisé ❌</p>");
    }
    $email_check->close();

    // Générer un mot de passe aléatoire
    function generatePassword($length = 10) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }

    $mot_de_passe = generatePassword();
    $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Préparer la requête d'insertion
    if ($date_validite === null) {
        $stmt = $conn->prepare("INSERT INTO pharmacies (nom, adresse, nb_collaborateurs, email, telephone, contact, date_inscription, date_validite, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NULL, ?)");
        $stmt->bind_param(
            "ssisssss",
            $nom, $adresse, $nb_collaborateurs, $email, $telephone, $contact, $date_inscription, $mot_de_passe_hash
        );
    } else {
        $stmt = $conn->prepare("INSERT INTO pharmacies (nom, adresse, nb_collaborateurs, email, telephone, contact, date_inscription, date_validite, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssissssss",
            $nom, $adresse, $nb_collaborateurs, $email, $telephone, $contact, $date_inscription, $date_validite, $mot_de_passe_hash
        );
    }

    if (!$stmt) {
        die("Erreur SQL : " . $conn->error);
    }

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "<h2>Inscription réussie ✅</h2>";
        echo "<p>Mot de passe généré : <strong>$mot_de_passe</strong></p>";
        echo "<p>Notez bien ce mot de passe pour vous connecter.</p>";
        echo "<a href='login.html'>Se connecter</a>";
    } else {
        die("<p style='color:red;'>Erreur lors de l'inscription : " . $stmt->error . "</p>");
    }

    $stmt->close();
}

$conn->close();
?>
