<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/clara/assets/images/logo-onglet.png">
    <link rel="stylesheet" href="/clara/assets/css/style.css">
    <link rel="stylesheet" href="/clara/assets/css/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="/clara/assets/js.js"></script>
    <title>Demandes inscription</title>
</head>
<body> 
<?php
    include __DIR__ . '/../../templates/header_admin.php';
    require_once '../../models/database.php';
    require_once '../../models/establishment.php';
    
    // Vérifier si l'ID est passé dans l'URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $conn = getConnexion();
        $id = intval($_GET['id']); 
        $establishment = getEstablishmentById($conn, $id); // Fonction pour récupérer les etablissement par ID

        // Vérifier si la demande existe
        if (!$establishment) {
            echo "Aucune demande trouvée avec cet ID.";
            exit;
        }

    } else {
        echo "ID non valide.";
        exit;
    }
?>
<main class="dashboard">
<div class="container-title"><h2>Détails</h2></div>
    <div class="details">
        <table class="table-details">
            <tr>
                <td><strong>id :</strong></td>
                <td><?= htmlspecialchars($establishment['id']); ?></td>
            </tr>
            <tr>
                <td><strong>Nom domaine :</strong></td>
                <td><?= htmlspecialchars($establishment['firstname']); ?></td>
            </tr>
            <tr>
                <td><strong>Telephone:</strong></td>
                <td><?= htmlspecialchars($establishment['phone']); ?></td>
            </tr>
            <tr>
                <td><strong>Adresse :</strong></td>
                <td><?= htmlspecialchars($establishment['adresse']); ?></td>
            </tr>
            <tr>
                <td><strong>Email :</strong></td>
                <td><?= htmlspecialchars($establishment['mail']); ?></td>
            </tr>
            <tr>
                <td><strong>Siret :</strong></td>
                <td><?= htmlspecialchars($establishment['siret']); ?></td>
            </tr>
            <tr>
                <td><strong>Type d'etablissement :</strong></td>
                <td><?= htmlspecialchars($establishment['type_role']); ?></td>
            </tr>
            <tr>
                <td><strong>Site web :</strong></td>
                <td><?= htmlspecialchars($establishment['site']); ?></td>
            </tr>
            <tr>
                <td><strong>Description :</strong></td>
                <td><?= htmlspecialchars_decode($establishment['description']); ?></td>
            </tr>
        </table>
        <div class="details-list">
            <p><strong>Nom de domaine :</strong></p>
            <p><?= htmlspecialchars($establishment['firstname']); ?></p>
            <p><strong>ID :</strong></p>
            <p><?= htmlspecialchars($establishment['id']); ?></p>
            <p><strong>Téléphone :</strong></p>
            <p><?= htmlspecialchars($establishment['phone']); ?></p>
            <p><strong>Adresse :</strong></td>
            <p><?= htmlspecialchars($establishment['Adresse']); ?></p>
            <p><strong>Email :</strong></p>
            <p><?= htmlspecialchars($establishment['mail']); ?></p>
            <p><strong>Siret :</strong></p>
            <p><?= htmlspecialchars($establishment['siret']); ?></p>
            <p><strong>Siret :</strong></p>
            <p><?= htmlspecialchars($establishment['type_role']); ?></p>
            <p><strong>Site Web :</strong></p>
            <p><?= htmlspecialchars($establishment['site']); ?></p>
            <p><strong>Description :</strong></p>
            <p><?= htmlspecialchars_decode($establishment['description']); ?></p>

        </div>
        <div class="btn-container">
        <button>
            <a href="/clara/views/admin/establishment.php" class="btn-back">Retour</a>
        </button>
        </div>
      
    </div>
</div>
</main>
 
</body>
</html>