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

<<<<<<< HEAD

=======
<<<<<<< HEAD
    // Vérifier si l'email existe déjà
=======
<<<<<<< HEAD
>>>>>>> c9512eacf1c8fe1343e3f03ecd3d3d640d049829
    // Vérifier si le nom existe déjà
    $nom_check = $conn->prepare("SELECT id FROM pharmacies WHERE nom = ?");
    $nom_check->bind_param("s", $nom);
    $nom_check->execute();
    $nom_check_result = $nom_check->get_result();

    if ($nom_check_result->num_rows > 0) {
        die("<p style='color:red;'>Erreur : ce nom est déjà utilisé ❌</p>");
    }
    $nom_check->close();

    // Vérifier si l'adresse' existe déjà
    $adresse_check = $conn->prepare("SELECT id FROM pharmacies WHERE adresse = ?");
    $adresse_check->bind_param("s", $adresse);
    $adresse_check->execute();
    $adresse_check_result = $adresse_check->get_result();

    if ($adresse_check_result->num_rows > 0) {
        die("<p style='color:red;'>Erreur : cette adresse est déjà utilisé ❌</p>");
    }
    $adresse_check->close();

    // Vérifier si le numéro de téléphone  existe déjà
    $telephone_check = $conn->prepare("SELECT id FROM pharmacies WHERE telephone = ?");
    $telephone_check->bind_param("s", $telephone);
    $telephone_check->execute();
    $telephone_check_result = $telephone_check->get_result();

    if ($telephone_check_result->num_rows > 0) {
        die("<p style='color:red;'>Erreur : ce numéro de téléphone est déjà utilisé ❌</p>");
    }
    $telephone_check->close();

    // Vérifier si l'email existe déjà

    // Vérifier si l'email existe déjà 
<<<<<<< HEAD
=======
>>>>>>> b827b1e455ab52c1429be137ed20c5d11e55bccb
>>>>>>> 19b40224b0a60e20d7d68ac4b3a9d9b320eaac4c
>>>>>>> c9512eacf1c8fe1343e3f03ecd3d3d640d049829
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
