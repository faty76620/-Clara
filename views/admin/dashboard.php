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
    <title>Demandes inscription</title>
</head>   
<body>
    <?php 
    require_once '../../models/database.php';
    require_once '../../models/send_mail.php';
    $conn = getConnexion();
    // Récupérer les demandes en attente
    $query = "SELECT * FROM requests WHERE status = 'pending'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $pendingRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <h2>Demandes d'inscription en attente</h2>

    <?php if (empty($pendingRequests)): ?>
        <p>Aucune demande en attente.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom de l'établissement</th>
                    <th>Nom du responsable</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingRequests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['firstname_establishment']); ?></td>
                        <td><?php echo htmlspecialchars($request['lastname_admin']); ?></td>
                        <td><?php echo htmlspecialchars($request['mail_admin']); ?></td>
                        <td>
                            <a href="/clara/controllers/adminController.php?action=approve&id=<?php echo $request['id']; ?>">Approuver</a>
                            <a href="/clara/controllers/adminController.php?action=reject&id=<?php echo $request['id']; ?>">Rejeter</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>