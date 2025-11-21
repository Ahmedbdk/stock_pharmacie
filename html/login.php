<?php
session_start();

// 🔥 Si l'utilisateur est déjà connecté → on le redirige
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
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

      <!-- ENVOI DU FORMULAIRE VERS check_login.php -->
      <form id="formConnexion" action="check_login.php" method="POST">
        <label for="email">Email *</label>
        <input
          type="email"
          id="email"
          name="email"
          placeholder="Votre email"
          required
        />

        <label for="password">Mot de passe *</label>
        <input
          type="password"
          id="password"
          name="password"
          placeholder="Votre mot de passe"
          autocomplete="off"
          required
        />

        <button type="submit">Se connecter</button>
      </form>

      <p><a href="forgot_password.html">Mot de passe oublié ?</a></p>
      <p>Pas encore inscrit ? <a href="form_inscription.html">S'inscrire</a></p>
    </main>
  </body>
</html>
