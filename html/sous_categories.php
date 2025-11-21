<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['cat'])) {
    die("Aucune catégorie sélectionnée");
}

// ⚠️ NE PAS décoder, ne pas toucher
$cat = $_GET['cat'];

// Connexion BDD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacie";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// 🔥 Requête EXACTE
$stmt = $conn->prepare("SELECT DISTINCT sous_categorie FROM produits WHERE categorie = ?");
$stmt->bind_param("s", $cat);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Sous-catégories</title>
</head>
<body>

<a href="produits.php"><button>← Retour aux catégories</button></a>

<h1>Sous-catégories pour : <?php echo htmlspecialchars($cat); ?></h1>

<table border="1">
    <thead>
        <tr>
            <th>Sous-catégorie</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $sous = $row['sous_categorie'];

                echo "<tr>
                        <td>
                            <form action='produits_sous_categorie.php' method='GET'>
                                <input type='hidden' name='sous' value=\"$sous\">
                                <button type='submit'>$sous</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td>Aucune sous-catégorie trouvée</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
