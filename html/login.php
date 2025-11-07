<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacie";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur connexion BDD : " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT id, nom, password, date_validite, blocked FROM pharmacies WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

       
        if ($user['blocked']) {
            echo "<p style='color:red;'>Compte bloqué, veuillez contacter un membre du support ❌</p>";
        }
        else {
            
            if (password_verify($password_input, $user['password'])) {

                $now = new DateTime();
                $valid_until = new DateTime($user['date_validite']);

                
                if ($now > $valid_until) {
                    $update = $conn->prepare("UPDATE pharmacies SET blocked = 1 WHERE id = ?");
                    $update->bind_param("i", $user['id']);
                    $update->execute();
                    $update->close();

                    echo "<p style='color:red;'>Compte bloqué, veuillez contacter un membre du support ❌</p>";
                } else {
                    
                    $valid_until->modify("+3 weeks");

                    $update = $conn->prepare("UPDATE pharmacies SET date_validite = ? WHERE id = ?");
                    $new_valid_date = $valid_until->format("Y-m-d");
                    $update->bind_param("si", $new_valid_date, $user['id']);
                    $update->execute();
                    $update->close();

                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nom'] = $user['nom'];

                    echo "<h2>Bienvenue, " . htmlspecialchars($user['nom']) . " 🎉</h2>";
                    echo "<p style='color:green;'>Connexion réussie ✅</p>";
                    echo "<p>Nouvelle validité : " . $new_valid_date . "</p>";
                    echo "<a href='dashboard.php'>Aller au tableau de bord</a>";
                }
            } else {
                echo "<p style='color:red;'>Mot de passe incorrect ❌</p>";
            }
        }
    } else {
        echo "<p style='color:red;'>Email non trouvé ❌</p>";
    }

    $stmt->close();
}

$conn->close();
?>
