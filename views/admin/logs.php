<?php

require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/logs.php';


$conn = getConnexion();
$logs = getLogsWithUserInfo($conn);

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
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="/clara/assets/js.js"></script>
    <title>Gestion des établissements</title>
</head>   
<body class="bacground">
    <?php include TEMPLATE_DIR . '/header_admin.php'; ?>
    <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
    ?>
    <main class="dashboard">
        <div class="container-title"><h2>Journalisation des logs</h2></div>
        <table class="table-request">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Action</th>
                    <th>Utilisateur</th>
                    <th>Événement</th>
                    <th>Date et Heure</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)) : ?>
                    <?php foreach ($logs as $log) : ?>
                        <tr>
                            <td><?= htmlspecialchars($log['id']) ?></td>
                            <td><?= htmlspecialchars($log['action']) ?></td>
                            <td><?= htmlspecialchars($log['username']) ?></td>
                            <td><?= htmlspecialchars($log['description']) ?></td>
                            <td><?= htmlspecialchars($log['timestamp']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                <tr>
                    <td colspan="5">Aucun log disponible.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="cards-container">
            <?php if (!empty($logs)) : ?>
                <?php foreach ($logs as $log) : ?>
                    <div class="card-session">
                        <h3>Log ID: <?= htmlspecialchars($log['id']) ?></h3>
                        <p><strong>Action:</strong> <?= htmlspecialchars($log['action']) ?></p>
                        <p><strong>Utilisateur:</strong> <?= htmlspecialchars($log['username']) ?></p>
                        <p><strong>Événement:</strong><?= htmlspecialchars($log['description']) ?></p>
                        <p><strong>Date:</strong> <?= htmlspecialchars($log['timestamp']) ?></p>
                    </div>
                <?php endforeach; ?>
                <?php else : ?>
                <p>Aucun log disponible.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>