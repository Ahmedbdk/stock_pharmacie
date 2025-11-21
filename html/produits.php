<?php
session_start();

// 🔒 Empêcher l'accès si NON connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connexion BDD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacie";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// 🔥 Récupérer les catégories uniques
$sql = "SELECT DISTINCT categorie FROM produits";
$result = $conn->query($sql);

if (!$result) {
    die("Erreur SQL : " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Catégories</title>
</head>
<body>

<a href="dashboard.php"><button>← Retour au Dashboard</button></a>

<h1>Liste des Catégories</h1>

<table border="1">
    <thead>
        <tr>
            <th>Catégorie</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                // On récupère la valeur EXACTE
                $cat = $row['categorie'];

                echo "<tr>
                        <td>
                            <form action='sous_categories.php' method='GET'>
                                <input type='hidden' name='cat' value=\"$cat\">
                                <button type='submit'>$cat</button>
                            </form>
                        </td>
                      </tr>";
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
