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
    require_once '../../models/database.php';
    require_once '../../models/send_mail.php';
    require_once '../../models/request.php';

    $conn = getConnexion();
    // Récupérer les demandes en attente
    $pendingRequests = getPendingRequests();
    ?>
<section class="dashboard">
    <h2>Demandes d'inscription en attente</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Etablissement</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingRequests as $request): ?>
                    <tr>
                    <td><?php htmlspecialchars($request['firstname_admin']) . " " . htmlspecialchars($request['lastname_admin']); ?></td>
                    <td><?php htmlspecialchars($request['mail_admin']); ?></td>
                    <td><?php htmlspecialchars($request['firstname_establishment']); ?></td>
                    <td><?php htmlspecialchars($request['status']); ?></td>
                    <td></td>
                        <td>
                            <a class="success" href="/clara/controllers/adminController.php?action=approve&id=<?php echo $request['id']; ?>">Approuver</a>
                            <a class="error" href="/clara/controllers/adminController.php?action=reject&id=<?php echo $request['id']; ?>">Rejeter</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
</html>