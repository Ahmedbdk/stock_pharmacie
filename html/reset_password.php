<?php
session_start();

if (!isset($_SESSION["reset_email"])) {
    die("Accès non autorisé.");
}

$mysqli = new mysqli("localhost", "root", "", "pharmacie");

if ($mysqli->connect_errno) {
    die("Erreur MySQL : " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $new_pass = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $email = $_SESSION["reset_email"];

    $stmt = $mysqli->prepare("UPDATE pharmacies SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_pass, $email);
    $stmt->execute();

    // Nettoyer la session
    unset($_SESSION["reset_email"]);
    unset($_SESSION["reset_otp"]);

    echo "<h2>Mot de passe réinitialisé ✔</h2>";
    echo "<a href='login.html'>➡ Se connecter</a>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nouveau mot de passe</title>
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <main class="login-container">
    <h1>Nouveau mot de passe</h1>
    <form method="POST">
      <label>Nouveau mot de passe *</label>
      <input type="password" name="password" required>
      <button type="submit">Confirmer</button>
    </form>
  </main>
</body>
</html>
