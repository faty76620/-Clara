<?php
session_start();

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: /clara/views/auth/login.php");
    exit();
}

// Récupérer le prénom de l'utilisateur depuis la session
$lastname = isset($_SESSION['lastname']) && !empty($_SESSION['lastname']) ? $_SESSION['lastname'] : 'Utilisateur';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/clara/assets/images/logo-onglet.png">
    <link rel="stylesheet" href="/clara/assets/css/style.css">
    <link rel="stylesheet" href="/clara/assets/css/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/15ca748f1e.js" crossorigin="anonymous"></script>
    <script defer src="/clara/assets/js.js"></script>
    <title>Tableau de bord</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../templates/header_manager.php'; ?>
    <main class="dashboard">
        <h2>Bonjour, <?php echo htmlspecialchars($lastname); ?> !</h2>
       
    </main>
</body>
</html>
