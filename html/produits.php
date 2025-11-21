<?php
// Connexion BDD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacie";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// 🔥 Récupérer les catégories SANS DOUBLONS
$sql = "SELECT DISTINCT categorie FROM produits";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Catégories</title>
</head>
<body>

<h1>Liste des Catégories</h1>

<table border="1">
    <thead>
        <tr>
            <th>Nom</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>".$row['categorie']."</td></tr>";
            }
        } else {
            echo "<tr><td>Aucune catégorie trouvée</td></tr>";
        }

        $conn->close();
        ?>
    </tbody>
</table>

</body>
</html>
