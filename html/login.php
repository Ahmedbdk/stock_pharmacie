<?php
session_start();

// 🔒 Si déjà connecté → redirection
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// 🚫 Empêcher la mise en cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <title>Connexion Pharmacie</title>
    <link rel="stylesheet" href="../css/login.css">
  </head>
  <body>
    <main class="login-container">
      <h1>Connexion</h1>

      <form id="formConnexion" action="check_login.php" method="POST">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe *</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
      </form>

      <p><a href="forgot_password.html">Mot de passe oublié ?</a></p>
      <p>Pas encore inscrit ? <a href="form_inscription.html">S'inscrire</a></p>
    </main>
  </body>
</html>
