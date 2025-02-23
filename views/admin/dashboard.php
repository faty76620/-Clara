<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/clara/assets/images/logo-onglet.png">
    <link rel="preload" as="image" href="/clara/assets/images/img-banner.jpg">
    <link rel="stylesheet" href="/clara/assets/css/style.css">
    <link rel="stylesheet" href="/clara/assets/css/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/15ca748f1e.js" crossorigin="anonymous"></script>
    <script defer src="/clara/assets/js.js"></script>
    <title>Tableau de bord</title>
</head>
<body>
<?php 
        include __DIR__ . '/../../templates/header_admin.php'; ?>
    <main class="dashboard main">
    <!-- AFFICHAGE DES MESSAGES DE CONFIRMATION -->
    <?php if (isset($_SESSION['success'])): ?>
    <p class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <h2>Demandes en attente</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom Admin</th>
                <th>Email</th>
                <th>Établissement</th>
                <th>Adresse</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (!isset($pendingRequests)) {
                $pendingRequests = []; // Initialisation en tant que tableau vide
            }
            
            if (count($pendingRequests) > 0): ?>
                <?php foreach ($pendingRequests as $request): ?>
                    <tr>
                        <td><?= htmlspecialchars($request['id']); ?></td>
                        <td><?= htmlspecialchars($request['firstname_admin']) . " " . htmlspecialchars($request['name_admin']); ?></td>
                        <td><?= htmlspecialchars($request['mail_admin']); ?></td>
                        <td><?= htmlspecialchars($request['firstname_establishment']); ?></td>
                        <td><?= htmlspecialchars($request['adresse']); ?></td>
                        <td>
                            <!-- LIEN POUR ACCEPTER LA DEMANDE -->
                            <div class="btn-action accept" >
                                <a href="/clara/controllers/adminController.php?action=accept_request&id=<?= $request['id']; ?>" 
                               >Accepter</a>
                            </div>
                            <!-- LIEN POUR REFUSER LA DEMANDE -->
                            <div class="btn-action reject">
                                <a href="/clara/controllers/adminController.php?action=reject_request&id=<?= $request['id']; ?>" 
                               >Refuser</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6"  >Aucune demande en attente.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </main>
</body>
</html>
