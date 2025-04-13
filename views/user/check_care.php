<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/caregiver.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/establishment.php';

$conn = getConnexion();

$caregiverId = $_SESSION['user_id']; // ID du soignant connecté 
$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];

$search = $_GET['search'] ?? null;
$assignedPatients = getAssignedPatientsWithCare($conn, $caregiverId, $search);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="/clara/assets/js.js"></script>
    <title>Interventions</title>
</head>
<body class="body-background">
    <?php 
    include TEMPLATE_DIR . '/header_user.php'; 
    ?>
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
    <div class="container-title">
        <h2>Interventions de <?= htmlspecialchars($firstname) . ' ' . htmlspecialchars($lastname) ?></h2>
        </div>
        <form method="GET">
                <div class="dashboard-search">
                <form method="GET" style="margin-bottom: 20px;">
    <input type="text" name="search" placeholder="Recherche nom, prénom, ID ou naissance">
    <button type="submit">Rechercher</button>
</form>

                    <div class="reset"><a href="check_care.php" class="btn-reset"><i class="fas fa-redo"></i></a></div> 
                </div>
            </form>
        <div class="tabs">
            <button id="btn-prevue" class="tab-button active" onclick="showTab('prevue')">
                <i class="fas fa-calendar-check text-primary"></i><span class="tab-text">Interventions prévue</span>
            </button>
            <button id="btn-valide" class="tab-button" onclick="showTab('valide')">
               <i class="fas fa-check-circle text-success"></i> <span class="tab-text">Interventions imprévue</span>
            </button>
            <button id="btn-imprevue" class="tab-button" onclick="showTab('imprevue')">
               <i class="fas fa-exclamation-triangle text-warning"></i> <span class="tab-text">Interventions à validées</span>
            </button>
        </div>

        <div id="prevue" class="tab-content active">
        <?php if (!empty($assignedPatients)) : ?>
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date de debut</th>
                    <th>Date de fin</th>
                    <th>Jour/Semaine</th>
                    <th>Créneau</th>
                    <th>Type de soins</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assignedPatients as $patient) : ?>
                    <tr>
                        <td><?= htmlspecialchars($patient['care_id']) ?></td>
                        <td><?= htmlspecialchars($patient['lastname']) ?></td>
                        <td><?= htmlspecialchars($patient['firstname']) ?></td>
                        <td><?= htmlspecialchars($patient['care_start_date']) ?></td>
                        <td><?= htmlspecialchars($patient['care_start_date']) ?></td>
                        <td><?= htmlspecialchars($patient['days_of_week']) ?></td>
                        <td><?= htmlspecialchars($patient['time_slot']) ?></td>
                        <td><?= htmlspecialchars($patient['care_type']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Aucun patient assigné.</p>
    <?php endif; ?>
</div>
<!-- VERSION CARTE -->
<div class="cards-container">
                    <?php if (!empty($assignedPatients)) : ?>
                        <?php foreach ($assignedPatients as $patient) : ?>
                            <div class="card-session">
                                <h3><?= htmlspecialchars($patient['lastname']) ?>&nbsp;&nbsp;<?= htmlspecialchars($patient['firstname']); ?></h3>
                                <p><strong>ID :</strong> <?= htmlspecialchars($patient['care_id']) ?></p>
                                <p><strong>Date de début :</strong> <?= htmlspecialchars($patient['care_start_date']) ?></p>
                                <p><strong>Date de fin :</strong> <?= htmlspecialchars($patient['care_end_date']) ?></p>
                                <p><strong>Jour/Semaine :</strong> <?= htmlspecialchars($patient['days_of_week']) ?></p>
                                <p><strong>Créneau :</strong> <?= htmlspecialchars($patient['time_slot']) ?></p>
                                <p><strong>Type de soins :</strong> <?= htmlspecialchars($patient['care_type']) ?></p>
                            </div>
                        <?php endforeach; ?>
                            <?php else : ?>
                            <p>Aucun patient assigné.</p>
                    <?php endif; ?>
                </div>
            </section>
    </main>
</body>
</html>