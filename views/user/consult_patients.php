<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/caregiver.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/establishment.php';
require_once MODEL_DIR . '/logs.php';

$conn = getConnexion();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$manager_establishment_id = $_SESSION['establishment_id'] ?? null;
$patients = getPatients($conn, $search, $manager_establishment_id);

$establishmentId = $_SESSION['establishment_id'] ?? null;
$caregivers = getCaregivers($conn, $search ?? '', $establishmentId);

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
    <?php include TEMPLATE_DIR . '/header_user.php'; ?>
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
        <div class="container-title"><h2>Informations</h2></div>

            <!-- FORMULAIRE DE RECHERCHE -->
            <form method="GET">
                <div class="dashboard-search">
                    <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search); ?>">
                    <button type="submit">Rechercher</button>
                    <div class="reset"><a href="folders_patients.php" class="btn-reset"><i class="fas fa-redo"></i></a></div> 
                </div>
            </form>
            <!-- TABLEAU DES PATIENTS -->
            <section id="patient">
                <table class="table-responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date admission</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Date de naissance</th>
                            <th>Genre</th>
                            <th>Etablissement</th>
                            <th>Détails</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($patients)) : ?>
                            <?php foreach ($patients as $patient) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($patient['patient_id']) ?></td>
                                    <td><?= htmlspecialchars($patient['created_at']) ?></td>
                                    <td><?= htmlspecialchars($patient['lastname']) ?></td>
                                    <td><?= htmlspecialchars($patient['firstname']) ?> </td>
                                    <td><?= htmlspecialchars($patient['date_of_birth']) ?> </td>
                                    <td><?= htmlspecialchars($patient['gender']) ?></td>
                                    <td><?= htmlspecialchars($patient['establishment_name']) ?></td>
                                    <td>
                                        <a href="details-patient.php?id=<?= htmlspecialchars($patient['patient_id']); ?>" class="btn-card detail-plus">Détails</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="9">Aucun patient trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- VERSION CARTE -->
                <div class="cards-container">
                    <?php if (!empty($patients)) : ?>
                        <?php foreach ($patients as $patient) : ?>
                            <div class="card-session">
                                <h3><?= htmlspecialchars($patient['lastname']) ?>&nbsp;&nbsp;<?= htmlspecialchars($patient['firstname']); ?></h3>
                                <p><strong>ID :</strong> <?= htmlspecialchars($patient['patient_id']) ?></p>
                                <p><strong>Date inscription :</strong> <?= htmlspecialchars($patient['created_at']) ?></p>
                                <p><strong>Date de naissance :</strong> <?= htmlspecialchars($patient['date_of_birth']) ?></p>
                                <p><strong>Genre :</strong> <?= htmlspecialchars($patient['gender']) ?></p>
                                <div class="card-actions">
                                    <a href="details-patient.php?id=<?= htmlspecialchars($patient['patient_id']); ?>" class="btn-card detail-plus">Détails</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                            <?php else : ?>
                            <p>Aucun patient.</p>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </body>
</html>