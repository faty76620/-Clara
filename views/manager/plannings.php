<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/planning.php';
require_once MODEL_DIR . '/logs.php';

$conn = getConnexion();

// Récupérer les plannings du manager
$schedules = getSchedules($conn, 'week', date('Y-m-d'));
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
    <title>Dossiers patients</title>
</head>
<body>
    <?php include TEMPLATE_DIR . '/header_manager.php'; ?>
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
        <div class="container-title"><h2>Gestion des plannings</h2></div>
              <!-- Ajouter un planning -->
                <div class="btn-container">
                    <button class="add-schedule" style="width: 25%;">
                        <a href="add-planning.php"><i class="fas fa-plus"></i> Ajouter</a>
                    </button>
                </div>
            <!-- Tableau des plannings -->
            <table class="table-responsive">
                <thead>
                    <tr>
                        <th>Soignant</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $schedule): ?>
                        <tr>
                            <td><?= htmlspecialchars($schedule['user_firstname'] . ' ' . $schedule['user_lastname']) ?></td>
                            <td><?= htmlspecialchars($schedule['patient_firstname'] . ' ' . $schedule['patient_lastname']) ?></td>
                            <td><?= date('d/m/Y', strtotime($schedule['start_datetime'])) ?></td>
                            <td><?= date('H:i', strtotime($schedule['start_datetime'])) ?></td>
                            <td><?= date('H:i', strtotime($schedule['end_datetime'])) ?></td>
                            <td>
                                <a href="edit_schedule.php?schedule_id=<?= $schedule['id'] ?>">Modifier</a> |
                                <a href="delete_schedule.php?schedule_id=<?= $schedule['id'] ?>">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </main>
</body>
</html>

