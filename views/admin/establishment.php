<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/establishment.php';
require_once MODEL_DIR . '/logs.php';

$conn = getConnexion();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$pendingEstablishments = getPendingEstablishments($conn, $search);
$approvedEstablishments = getApprovedEstablishments($conn, $search);
$rejectedEstablishments = getRejectedEstablishments($conn, $search);

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
<body class="body-background">
    <?php include TEMPLATE_DIR . '/header_admin.php'; ?>
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
    <div class="container-title"><h2>Gestions des Etablissements</h2></div>

    <!-- FORMULAIRE DE RECHERCHE -->
    <form method="GET">
        <div class="dashboard-search">
            <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search); ?>">
            <button type="submit">Rechercher</button>
            <div class="reset"><a href="establishment.php" class="btn-reset"><i class="fas fa-redo"></i></a></div> 
        </div>
    </form>

    <!-- ONGLET POUR FILTRER LES STATUTS -->
    <div class="tabs">
        <button id="tab-pending" class="tab-button active" onclick="showTab('pending')"><i class="fas fa-clock"></i> <span class="tab-text">En attente</span></button>
        <button id="tab-approved" class="tab-button" onclick="showTab('approved')"><i class="fas fa-check-circle"></i> <span class="tab-text">Acceptées</span></button>
        <button id="tab-rejected" class="tab-button" onclick="showTab('rejected')"><i class="fas fa-times-circle"></i> <span class="tab-text">Refusées</span></button>
    </div>

    <!-- TABLEAU DES DEMANDES EN ATTENTE -->
    <section id="pending" class="tab-content active">
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Email</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pendingEstablishments)) : ?>
                    <?php foreach ($pendingEstablishments as $establishment) : ?>
                        <tr>
                            <td><?= htmlspecialchars($establishment['id']) ?></td>
                            <td><?= htmlspecialchars($establishment['created_at']) ?></td>
                            <td><?= htmlspecialchars($establishment['firstname']) ?></td>
                            <td><?= htmlspecialchars($establishment['phone']) ?></td>
                            <td><?= htmlspecialchars($establishment['adresse']) ?></td>
                            <td><?= htmlspecialchars($establishment['mail']) ?></td>
                            <td>
                                <a href="details-establishment.php?id=<?= htmlspecialchars($establishment['id']); ?>" class="btn-card detail-plus">Détails</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">Aucun établissement en attente.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- VERSION CARTE -->
        <div class="cards-container">
            <?php if (!empty($pendingEstablishments)) : ?>
                <?php foreach ($pendingEstablishments as $establishment) : ?>
                    <div class="card-session">
                        <h3><?= htmlspecialchars($establishment['firstname']) ?></h3>
                        <p><strong>ID :</strong> <?= htmlspecialchars($establishment['id']) ?></p>
                        <p><strong>Date :</strong> <?= htmlspecialchars($establishment['created_at']) ?></p>
                        <p><strong>Téléphone :</strong> <?= htmlspecialchars($establishment['phone']) ?></p>
                        <p><strong>Adresse :</strong> <?= htmlspecialchars($establishment['adresse']) ?></p>
                        <p><strong>Email :</strong> <?= htmlspecialchars($establishment['mail']) ?></p>
                        <div class="card-actions">
                            <a href="details-establishment.php?id=<?= htmlspecialchars($establishment['id']); ?>" class="btn-card detail-plus">Détails</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun établissement en attente.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- TABLEAU DES ETABLISSEMENTS ACCEPTÉS -->
    <section id="approved" class="tab-content">
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Creer le</th>
                    <th>Modifier</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Email</th>
                    <th>Détails</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($approvedEstablishments)) : ?>
                    <?php foreach ($approvedEstablishments as $establishment) : ?>
                        <tr>
                            <td><?= htmlspecialchars($establishment['id']) ?></td>
                            <td><?= htmlspecialchars($establishment['created_at']) ?></td>
                            <td><?= htmlspecialchars($establishment['date_modify']?? '') ?></td>
                            <td><?= htmlspecialchars($establishment['firstname']) ?></td>
                            <td><?= htmlspecialchars($establishment['phone']) ?></td>
                            <td><?= htmlspecialchars($establishment['adresse']) ?></td>
                            <td><?= htmlspecialchars($establishment['mail']) ?></td>
                            <td>
                                <a href="details-establishment.php?id=<?= htmlspecialchars($establishment['id']); ?>" class="btn-card detail-plus">Détails</a>
                            </td>
                            <td>
                                <div class="action">
                                    <a href="../../views/admin/edit-establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-card edit">Modifier</a>
                                    <a href="../../controllers/delete-establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-card delete" onclick="return confirm('Voulez-vous vraiment supprimer cet établissement ?');">Supprimer</a>
                                <div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8">Aucun établissement accepté.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

         <!-- VERSION CARTE -->
         <div class="cards-container">
            <?php if (!empty($approvedEstablishments)) : ?>
                <?php foreach ($approvedEstablishments as $establishment) : ?>
                    <div class="card-session">
                        <h3><?= htmlspecialchars($establishment['firstname']) ?></h3>
                        <p><strong>ID :</strong> <?= htmlspecialchars($establishment['id']) ?></p>
                        <p><strong>Date :</strong> <?= htmlspecialchars($establishment['created_at']) ?></p>
                        <p><strong>Téléphone :</strong> <?= htmlspecialchars($establishment['phone']) ?></p>
                        <p><strong>Adresse :</strong> <?= htmlspecialchars($establishment['adresse']) ?></p>
                        <p><strong>Email :</strong> <?= htmlspecialchars($establishment['mail']) ?></p>
                        <div class="card-actions">
                            <a href="details-establishment.php?id=<?= htmlspecialchars($establishment['id']); ?>" class="btn-card detail-plus">Détails</a>
                            <a href="../../views/admin/edit-establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-card edit"><i class="fas fa-edit"></i></a>
                            <a href="../../controllers/delete-establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-card delete" onclick="return confirm('Voulez-vous vraiment supprimer cet établissement ?');"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun établissement en attente.</p>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- TABLEAU DES ÉTABLISSEMENTS REFUSÉS -->
    <section id="rejected" class="tab-content">
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Email</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rejectedEstablishments)) : ?>
                    <?php foreach ($rejectedEstablishments as $establishment) : ?>
                        <tr>
                            <td><?= htmlspecialchars($establishment['id']) ?></td>
                            <td><?= htmlspecialchars($establishment['created_at']) ?></td>
                            <td><?= htmlspecialchars($establishment['firstname']) ?></td>
                            <td><?= htmlspecialchars($establishment['phone']) ?></td>
                            <td><?= htmlspecialchars($establishment['adresse']) ?></td>
                            <td><?= htmlspecialchars($establishment['mail']) ?></td>
                            <td>
                                <a href="details-establishment.php?id=<?= htmlspecialchars($establishment['id']); ?>" class="btn-card detail-plus">Détails</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">Aucun établissement refusé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

         <!-- VERSION CARTE -->
         <div class="cards-container">
            <?php if (!empty($rejectedEstablishments)) : ?>
                <?php foreach ($rejectedEstablishments as $establishment) : ?>
                    <div class="card-session">
                        <h3><?= htmlspecialchars($establishment['firstname']) ?></h3>
                        <p><strong>ID :</strong> <?= htmlspecialchars($establishment['id']) ?></p>
                        <p><strong>Date :</strong> <?= htmlspecialchars($establishment['created_at']) ?></p>
                        <p><strong>Téléphone :</strong> <?= htmlspecialchars($establishment['phone']) ?></p>
                        <p><strong>Adresse :</strong> <?= htmlspecialchars($establishment['adresse']) ?></p>
                        <p><strong>Email :</strong> <?= htmlspecialchars($establishment['mail']) ?></p>
                        <div class="card-actions">
                            <a href="details-establishment.php?id=<?= htmlspecialchars($establishment['id']); ?>" class="btn-card details">Détails</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun établissement en attente.</p>
            <?php endif; ?>
        </div>
    </section>
</main>
</body>
</html>



