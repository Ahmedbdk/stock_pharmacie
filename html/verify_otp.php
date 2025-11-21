<?php
session_start();

if (!isset($_SESSION["reset_email"]) || !isset($_SESSION["reset_otp"])) {
    die("Session expirée. <a href='forgot_password.php'>Réessayer</a>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($_POST["otp"] == $_SESSION["reset_otp"]) {
        // Le code est correct !
        header("Location: reset_password.php");
        exit();
    } else {
        echo "<p style='color:red;'>❌ Code incorrect</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Vérifier OTP</title>
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <main class="login-container">
    <h1>Entrez votre code</h1>
    <form method="POST">
      <label>Code OTP *</label>
      <input type="number" name="otp" required>
      <button type="submit">Vérifier</button>
    </form>
  </main>
</body>
</html>
