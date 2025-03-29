<?php
require_once __DIR__ . '/../../config.php'; 
require_once TEMPLATE_DIR . '/session_start.php';  

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/views/auth/login.php");
    exit();
}

// Affichage des messages de session
if (isset($_SESSION['success'])) {
    echo '<div class="message success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="message error">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

// Récupérer le prénom de l'utilisateur depuis la session
$lastname = $_SESSION['lastname'] ?? 'Utilisateur';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/logo-onglet.png">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300..800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/15ca748f1e.js" crossorigin="anonymous"></script>
    <script defer src="<?= BASE_URL ?>/assets/js.js"></script>
    <title>Tableau de bord</title>
</head>
<body>
    <?php include TEMPLATE_DIR . '/header_admin.php'; ?>
    <main class="dashboard">
        <h2>Bonjour, <?= htmlspecialchars($lastname) ?> !</h2>
    </main>
</body>
</html>
