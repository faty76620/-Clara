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
    <title>Demandes inscription</title>
</head>   
<body>
    <?php 
    include __DIR__ . '/../../templates/header_admin.php';
    require_once '../../models/database.php';
    require_once '../../models/send_mail.php';
    require_once '../../models/request.php';

    $pendingRequests = getPendingRequests();
    // Récupération de la recherche
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    ?>
    <main class="dashboard">
        <h2>Demandes d'inscription en attente</h2>
        <form method="GET">
            <div class="dashboard-search">
                <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search); ?>">
                <button type="submit">Rechercher</button>
            </div>
        </form>
        <table class="table-request">
            <thead>
                <tr>
                    <th>Nom de l'entreprise</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>En savoir plus</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pendingRequests)) : ?>
                    <?php foreach ($pendingRequests as $request) : ?>
                        <tr>
                            <td><?= htmlspecialchars($request['firstname_establishment']); ?></td>
                            <td><?= htmlspecialchars($request['mail_admin']); ?></td>
                            <td><?= htmlspecialchars($request['created_at']); ?></td>
                            <td><?= htmlspecialchars($request['type_role']); ?></td>
                            <td><?= htmlspecialchars($request['status']); ?></td>
                            <td>
                                <a href="/clara/views/admin/requests.php?id=<?= $request['id']; ?>">Voir plus</a>
                            </td>
                            <td>
                                <div class="action">
                                    <a href="/clara/controllers/adminController.php?action=approve&id=<?= htmlspecialchars($request['id']); ?>" class="btn-dashboard btn-success">Approuver</a>
                                    <a href="/clara/controllers/adminController.php?action=reject&id=<?= htmlspecialchars($request['id']); ?>" class="btn-dashboard btn-reject">Rejeter</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else : ?>
                    <tr>
                        <td colspan="7">Aucune demande en attente.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- TABLETTE - MOBILE -->
        <div class="cards-request">
            <?php if (!empty($pendingRequests)) : ?>
                <?php foreach ($pendingRequests as $request) : ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($request['firstname_establishment']); ?></h3>
                        <p><?= htmlspecialchars($request['mail_admin']); ?></p>
                        <p><?= htmlspecialchars($request['created_at']); ?></p>
                        <p><?= htmlspecialchars($request['type_role']); ?></p>
                        <p><?= htmlspecialchars($request['status']); ?></p>
                        <div>
                            <a href="/clara/views/admin/requests.php?id=<?= $request['id']; ?>">Voir plus</a>
                        </div>
                        <div class="action">
                            <a href="/clara/controllers/adminController.php?action=approve&id=<?= htmlspecialchars($request['id']); ?>" class="btn-dashboard btn-success">Approuver</a>
                            <a href="/clara/controllers/adminController.php?action=reject&id=<?= htmlspecialchars($request['id']); ?>" class="btn-dashboard btn-reject">Rejeter</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php else : ?>
                <p>Aucune demande en attente.</p>
            <?php endif; ?>
        </div>
    </main>  
</body>
</html>