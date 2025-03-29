<?php

require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/user.php';

$conn = getConnexion();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// On récupère les utilisateurs selon leur rôle
$responsables = getUsersByRole($conn, 'Manager', $search);  // Récupérer les responsables
$utilisateurs = getUsersByRole($conn, 'Utilisateur', $search);  // Récupérer les utilisateurs
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
    <title>Gestion des utilisateurs</title>
</head>

<body class="body-background">
        <?php   include TEMPLATE_DIR . '/header_admin.php'; ?>
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
            <h2>Gestion des utilisateurs</h2>
        </div>

        <!-- FORMULAIRE DE RECHERCHE -->
        <form method="GET">
            <div class="dashboard-search">
                <input type="text" name="search" placeholder="Rechercher un utilisateur..." value="<?= htmlspecialchars($search); ?>">
                <button type="submit">Rechercher</button>
                <!-- Bouton pour réinitialiser la recherche -->
                <div class="reset"><a href="users.php" class="btn-reset"><i class="fas fa-redo"></i></a></div>
            </div>
        </form>

        <!-- ONGLET POUR BASCULER SUR LES RÔLES -->
        <div class="tabs">
            <button id="tab-responsables" class="tab-button active" onclick="showTab('responsables')">
                <i class="fas fa-user-tie"></i><span class="tab-text">Responsables</span>
            </button>
            <button id="tab-utilisateurs" class="tab-button" onclick="showTab('utilisateurs')">
                <i class="fas fa-users"></i><span class="tab-text">Utilisateurs</span>
            </button>
        </div>

        <!-- SECTION RESPONSABLES -->
        <div id="responsables" class="tab-content active">
            <table class="table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Etablissement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($responsables)) : ?>
                        <?php foreach ($responsables as $user) : ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['date_create']) ?></td>
                                <td><?= htmlspecialchars($user['firstname']) ?></td>
                                <td><?= htmlspecialchars($user['lastname']) ?></td>
                                <td><?= htmlspecialchars($user['mail']) ?></td>
                                <td><?= htmlspecialchars($user['role_name']) ?></td>
                                <td><?= htmlspecialchars($user['establishment_name'] ?: 'Aucun établissement') ?></td>

                                <td class="action">
                                    <a href="../../views/admin/edit-user.php?id=<?= htmlspecialchars($user['id']) ?>" class="btn-dashboard edit">Modifier</a>
                                    <a href="../../controllers/delete-user.php?id=<?= htmlspecialchars($user['id']) ?>" class="btn-dashboard delete" onclick="return confirm('Voulez-vous vraiment supprimer ce responsable ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8">Aucun responsable trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- AFFICHAGE TABLETTE ET MOBILE -->
            <div class="cards-container">
                <?php if (!empty($responsables)) : ?>
                    <?php foreach ($responsables as $user) : ?>
                        <div class="card-session">
                            <h3><?= htmlspecialchars($user['lastname']); ?>&nbsp;&nbsp;<?= htmlspecialchars($user['firstname']); ?></h3>
                            <p>Etablissement : <?= htmlspecialchars($user['establishment_name']) ?></p>
                            <p>ID : <?= htmlspecialchars($user['id']);?></p>
                            <p>Date inscription : <?= htmlspecialchars($user['date_create']) ?></p>
                            <p>Role : <?= htmlspecialchars($user['role_name']) ?></p>
                            <p>Email : <?= htmlspecialchars($user['mail']); ?></p>
                            <div class="card-actions">
                                <a href="../../views/admin/edit-user.php?id=<?= htmlspecialchars($user['id']) ?>" class="btn-card edit"><i class="fas fa-edit"></i></a>
                                <a href="../../controllers/delete-user.php?id=<?= htmlspecialchars($user['id']) ?>" class="btn-card delete" onclick="return confirm('Voulez-vous vraiment supprimer ce responsable ?');"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Aucune demande en attente.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- SECTION UTILISATEURS -->
        <div id="utilisateurs" class="tab-content">
            <table class="table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Etablissement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($utilisateurs)) : ?>
                        <?php foreach ($utilisateurs as $user) : ?>
                            <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['date_create']) ?></td>
                                <td><?= htmlspecialchars($user['firstname']) ?></td>
                                <td><?= htmlspecialchars($user['lastname']) ?></td>
                                <td><?= htmlspecialchars($user['mail']) ?></td>
                                <td><?= htmlspecialchars($user['role_name']) ?></td>
                                <td><?= htmlspecialchars($user['establishment_name'] ?: 'Aucun établissement') ?></td>
                                <td class="action">
                                    <a href="../../views/admin/edit-user.php?id=<?= htmlspecialchars($user['id']) ?>" class="btn-dashboard edit">Modifier</a>
                                    <a href="../../controllers/delete-user.php?id=<?= htmlspecialchars($user['id']) ?>" class="btn-dashboard delete" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8">Aucun utilisateur trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- AFFICHAGE TABLETTE ET MOBILE -->
            <div class="cards-container">
                <?php if (!empty($utilisateurs)) : ?>
                    <?php foreach ($utilisateurs as $user) : ?>
                        <div class="card-session">
                        <h3><?= htmlspecialchars($user['lastname']); ?>&nbsp;&nbsp;<?= htmlspecialchars($user['firstname']); ?></h3>
                            <p>Etablissement : <?= htmlspecialchars($user['establishment_name']) ?></p>
                            <p>ID : <?= htmlspecialchars($user['id']);?></p>
                            <p>Date inscription : <?= htmlspecialchars($user['date_create']) ?></p>
                            <p>Role : <?= htmlspecialchars($user['role_name']) ?></p>
                            <p>Email : <?= htmlspecialchars($user['mail']); ?></p>
                            <div class="card-actions">
                                <a href="../../views/admin/edit-user.php?id=<?= htmlspecialchars($user['id']) ?>" class="btn-card edit"><i class="fas fa-edit"></i></a>
                                <a href="../../controllers/delete-user.php?id=<?= htmlspecialchars($user['id']) ?>" class="btn-card delete" onclick="return confirm('Voulez-vous vraiment supprimer ce responsable ?');"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Aucune demande en attente.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>