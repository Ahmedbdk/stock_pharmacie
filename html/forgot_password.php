<?php
session_start();

// Connexion MySQL
$mysqli = new mysqli("localhost", "root", "", "pharmacie");

if ($mysqli->connect_errno) {
    die("Erreur MySQL : " . $mysqli->connect_error);
}

// Si le formulaire est envoyé
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email  = trim($_POST["email"]);
    $nom    = trim($_POST["nom"]);
    $date   = trim($_POST["date_creation"]); // format YYYY-MM-DD

    /*
    ------------------------------------------
    1️⃣ Vérifier si l'email existe
    ------------------------------------------
    */
    $check_email = $mysqli->prepare("SELECT id, nom, date_inscription FROM pharmacies WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows === 0) {
        die("<main class='login-container'>
                <h3 style='color:red;'>❌ Cet email n'existe pas.</h3>
                <a href='forgot_password.html'>Réessayer</a>
            </main>");
    }

    /*
    ------------------------------------------
    2️⃣ Vérifier le nom
    ------------------------------------------
    */
    $row = $result->fetch_assoc();

    if (trim(strtolower($row['nom'])) !== trim(strtolower($nom))) {
        die("<main class='login-container'>
                <h3 style='color:red;'>❌ Le nom ne correspond pas.</h3>
                <a href='forgot_password.html'>Réessayer</a>
            </main>");
    }

    /*
    ------------------------------------------
    3️⃣ Vérifier la date de création
    ------------------------------------------
    */
    if ($row['date_inscription'] !== $date) {
        die("<main class='login-container'>
                <h3 style='color:red;'>❌ La date de création est incorrecte.</h3>
                <a href='forgot_password.html'>Réessayer</a>
            </main>");
    }

    /*
    ------------------------------------------
    4️⃣ Générer un nouveau mot de passe
    ------------------------------------------
    */
    function generatePassword($length = 12) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }

    $new_pass = generatePassword();
    $hashed   = password_hash($new_pass, PASSWORD_BCRYPT);

    /*
    ------------------------------------------
    5️⃣ Mettre à jour le mot de passe
    ------------------------------------------
    */
    $update = $mysqli->prepare("UPDATE pharmacies SET password = ? WHERE email = ?");
    $update->bind_param("ss", $hashed, $email);
    $update->execute();

    /*
    ------------------------------------------
    6️⃣ Afficher le résultat final
    ------------------------------------------
    */
    echo "<main class='login-container'>
            <h2>Mot de passe réinitialisé ✔</h2>
            <p>Voici votre nouveau mot de passe :</p>
            <h1 style='color:green;'>$new_pass</h1>
            <p>Veuillez vous connecter avec ce nouveau code.</p>
            <a href='login.html'>➡ Se connecter</a>
          </main>";
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
      <input type="email" name="email" required placeholder="exemple@mail.com">

      <label>Nom *</label>
      <input type="text" name="nom" required placeholder="Nom de la pharmacie">

      <label>Date de création *</label>
      <input type="date" name="date_creation" required>

      <button type="submit">Réinitialiser</button>
    </form>

    <p><a href="login.html">Retour</a></p>
  </main>
</body>
</html>
