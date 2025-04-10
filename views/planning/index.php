<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/planning.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/patients.php';

$conn = getConnexion();

// Sécurité : rediriger si non connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /clara/views/auth/login.php');
    exit;
}

if ($_SESSION['role_id'] != 2)
 {
    $schedules = getAllPlannings($conn); // recupere tous les plannning

} else {
    $schedules = getPlanningsByUser($conn, $_SESSION['user_id']); //recupere son planning
}
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
    <title>Planning</title>
</head>
<body>

<?php include TEMPLATE_DIR . ($_SESSION['role_id'] == 2 ? '/header_manager.php' : '/header_user.php'); ?>
    <?php
        if (isset($_SESSION['success'])) {
            echo '<div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px;">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']); 
         }

        if (isset($_SESSION['error'])) {
            echo '<div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']); 
        }
    ?>
    
    <main class="dashboard">
        <div class="container-title"><h2>Planning</h2></div>
        <div class="btn-flex">
            <div class="add-planning-button">
                <a href="add.php"><i class="fas fa-calendar-plus"></i> </a>
            </div>
        </div>
        <!-- Onglets de vue planning -->
        <div class="tabs">
            <button id="tab-day" class="tab-button active" onclick="showPlanningTab('day')">
                <i class="fas fa-calendar-day"></i> <span class="tab-text">Journée</span>
            </button>
            <button id="tab-week" class="tab-button" onclick="showPlanningTab('week')">
                <i class="fas fa-calendar-week"></i> <span class="tab-text">Semaine</span>
            </button>
            <button id="tab-month" class="tab-button" onclick="showPlanningTab('month')">
                <i class="fas fa-calendar-alt"></i> <span class="tab-text">Mois</span>
            </button>
        </div>

        <!-- CONTENU DES ONGLET -->
        <div id="day" class="tab-content active">
            <?php include 'planning_day.php'; ?>
        </div>

        <div id="week" class="tab-content" style="display: none;">
            <?php include 'planning_week.php'; ?> 
        </div>

        <div id="month" class="tab-content" style="display: none;">
            <?php include 'planning_month.php'; ?>
        </div>
    </main>
</body>
</html>
