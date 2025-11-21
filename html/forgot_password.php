<?php
session_start();

// Connexion MySQL
$mysqli = new mysqli("localhost", "root", "", "pharmacie");

if ($mysqli->connect_errno) {
    die("Erreur MySQL : " . $mysqli->connect_error);
}

// Si on a soumis le formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);

    // Vérifier si email existe
    $stmt = $mysqli->prepare("SELECT id FROM pharmacies WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        die("<p style='color:red;'>❌ Cet email n'existe pas.</p>");
    }

    // Générer OTP à 6 chiffres
    $otp = rand(100000, 999999);

    // Sauvegarder en session pour simplifier
    $_SESSION["reset_email"] = $email;
    $_SESSION["reset_otp"] = $otp;

    // (Normalement tu enverrais un email, ici on simule)
    echo "<h2>Votre code OTP est : <span style='color:green;'>$otp</span></h2>";
    echo "<a href='verify_otp.php'>➡ Entrez votre code</a>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mot de passe oublié</title>
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <main class="login-container">
    <h1>Réinitialiser le mot de passe</h1>
    <form action="forgot_password.php" method="POST">
      <label>Email *</label>
      <input type="email" name="email" required placeholder="Votre email">
      <button type="submit">Envoyer le code</button>
    </form>
  </main>
</body>
</html>
