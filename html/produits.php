<?php
// produits.php

// 1️⃣ Connexion à la base de données
$servername = "localhost";
$username = "root";   // ton utilisateur MySQL
$password = "";       // ton mot de passe MySQL
$dbname = "pharmacie"; // ← nom correct de la base

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// 2️⃣ Récupération des produits Médical et Paramédical
$sql = "SELECT * FROM produits 
        WHERE categorie IN ('Médical', 'Paramédical')";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Produits</title>
</head>
<body>
  <h1>Produits</h1>

  <table border="1">
    <thead>
      <tr>
        <th>Nom</th>
        <th>Catégorie</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>".$row['nom']."</td>
                      <td>".$row['categorie']."</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='6'>Aucun produit trouvé</td></tr>";
      }

      $conn->close();
      ?>
    </tbody>
  </table>
</body>
</html>
