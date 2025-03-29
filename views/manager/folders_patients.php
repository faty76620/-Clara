<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patientS.php';
require_once MODEL_DIR . '/logs.php';

$conn = getConnexion();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$patients = getPatients($conn, $search);

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
    
    <main class="dashboard">
        <div class="container-title"><h2>Liste des Patients</h2></div>

        <!-- FORMULAIRE DE RECHERCHE -->
        <form method="GET">
            <div class="dashboard-search">
                <input type="text" name="search" placeholder="Rechercher un patient..." value="<?= htmlspecialchars($search); ?>">
                <button type="submit">Rechercher</button>
                <div class="reset"><a href="folders_patients.php" class="btn-reset"><i class="fas fa-redo"></i></a></div> 
            </div>
        </form>

         <!-- ONGLET POUR FILTRER LES LISTES -->
         <div class="tabs">
            <button id="tab-patient" class="tab-button active" onclick="showTab('patient')">
                <i class="fas fa-user-injured"></i> <span class="tab-text">Patients</span>
            </button>
            <button id="tab-caregiver" class="tab-button" onclick="showTab('caregivers')">
                <i class="fas fa-user-md"></i> <span class="tab-text">Soignants</span>
            </button>
            
        </div>

        <!-- TABLEAU DES PATIENTS -->
        <div class="patients">
            <table class="table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date admission</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de naissance</th>
                        <th>Genre</th>
                        <th>Détails</th>
                        <th>Actions</th>
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
                                <td>
                                    <a href="details-patient.php?id=<?= htmlspecialchars($patient['patient_id']); ?>" class="btn-card detail-plus">Détails</a>
                                </td>
                                <td>
                                <div class="action">
                                    <a href="<?= VIEW_DIR . '/manager/edit-patient.php?id=' . htmlspecialchars($patient['patient_id']) ?>" class="btn-card edit">Modifier</a>
                                    <a href="<?= CONTROLLER_DIR . '/manager/delete-patient.php?id=' . htmlspecialchars($patient['patient_id'])?>" 
                                        class="btn-card delete" 
                                        onclick="return confirm('Voulez-vous vraiment supprimer ce patient ?');">
                                        Supprimer
                                    </a>
                                </div>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8">Aucun patient trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- VERSION CARTE -->
            <div class="cards-container">
                <?php if (!empty($patients)) : ?>
                <?php foreach ($patients as $patient) : ?>
                    <div class="card-session">
                        <h3><?= htmlspecialchars($patient['lastname']) ?>&nbsp;&nbsp;<?= htmlspecialchars($user['firstname']); ?></h3> ?></h3>
                        <p><strong>ID :</strong> <?= htmlspecialchars($patient['patient_id']) ?></p>
                        <p><strong>Date inscription :</strong> <?= htmlspecialchars($patient['created_at']) ?></p>
                        <p><strong>Date de naissance :</strong> <?= htmlspecialchars($patient['date_of_birth']) ?></p>
                        <p><strong>Genre :</strong> <?= htmlspecialchars($patient['gender']) ?></p>
                        <div class="card-actions">
                            <a href="details_patient.php?id=<?= htmlspecialchars($patient['patient_id']); ?>" class="btn-card detail-plus">Détails</a>
                            <a href="../../views/manager/edit-patient.php?id=<?= htmlspecialchars($patient['id']) ?>" class="btn-card edit"><i class="fas fa-edit"></i></a>
                            <a href="../../controllers/delete-patient.php?id=<?= htmlspecialchars($patient['id']) ?>" class="btn-card delete" onclick="return confirm('Voulez-vous vraiment supprimer ce patient ?');"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun établissement en attente.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="caregivers">
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date admission</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Etablissement</th>
                </tr>
            </thead>
               
    </div>
    </main>
</body>
</html>
