<?php
session_start();

// Connexion BDD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacie";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur connexion BDD : " . $conn->connect_error);
}

// Vérifier formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password_input = $_POST['password'];

    // Vérifier si l'email existe
    $stmt = $conn->prepare("SELECT id, nom, password FROM pharmacies WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Email trouvé ?
    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // Vérifier le mot de passe
        if (password_verify($password_input, $user['password'])) {

            // Stocker en session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];

            // 🔥 REDIRECTION AUTOMATIQUE VERS LE DASHBOARD
            header("Location: dashboard.php");
            exit();

        } else {
            echo "<p style='color:red;'>Mot de passe incorrect ❌</p>";
        }

    } else {
        echo "<p style='color:red;'>Email non trouvé ❌</p>";
    }

    $stmt->close();
}

$conn->close();
?>
