<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// empêcher la mise en cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <script>
    // Bloquer le retour en arrière (DOIT être dans le <head>)
    history.pushState(null, "", location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    </script>

</head>
<body>

<h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?> 👋</h1>

<a href="produits.php"><button>Voir les Produits</button></a>

<a href="logout.php">
    <button style="background:red;color:white;">Déconnexion</button>
</a>

</body>
</html>
