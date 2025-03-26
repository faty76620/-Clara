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
<body class="background"> 
<?php
    include __DIR__ . '/../../templates/header_admin.php';
    require_once '../../models/database.php';
    require_once '../../models/request.php';
    
    // Vérifier si l'ID est passé dans l'URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $conn = getConnexion();
        $id = intval($_GET['id']); 
        $request = getRequestById($conn, $id); // Fonction pour récupérer la demande par ID

        // Vérifier si la demande existe
        if (!$request) {
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
        <div class="details-request">
            <table class="details">
                <tr>
                    <td><strong>id :</strong></td>
                    <td><?= htmlspecialchars($request['id']); ?></td>
                </tr>
                <tr>
                    <td><strong>Nom de domaine :</strong></td>
                    <td><?= htmlspecialchars($request['firstname_establishment']); ?></td>
                </tr>
                <tr>
                    <td><strong>Adresse :</strong></td>
                    <td><?= htmlspecialchars($request['adresse']); ?></td>
                </tr>
                <tr>
                    <td><strong>Telephone:</strong></td>
                    <td><?= htmlspecialchars($request['phone']); ?></td>
                </tr>
                <tr>
                    <td><strong>Email :</strong></td>
                    <td><?= htmlspecialchars($request['mail']); ?></td>
                </tr>
                <tr>
                    <td><strong>Siret :</strong></td>
                    <td><?= htmlspecialchars($request['siret']); ?></td>
                </tr>
                <tr>
                    <td><strong>Type d'etablissement :</strong></td>
                    <td><?= htmlspecialchars($request['type_role']); ?></td>
                </tr>
                <tr>
                    <td><strong>Email Etablissement :</strong></td>
                    <td><?= htmlspecialchars($request['mail']); ?></td>
                </tr>
                <tr>
                    <td><strong>Site web :</strong></td>
                    <td><?= htmlspecialchars($request['site']); ?></td>
                </tr>
                <tr>
                    <td><strong>Type de Rôle :</strong></td>
                    <td><?= htmlspecialchars($request['type_role']); ?></td>
                </tr>
                <tr>
                    <td><strong>Description :</strong></td>
                    <td><?= htmlspecialchars_decode($request['description']); ?></td>
                </tr>
                <tr>
                    <td><strong>Prénom Admin :</strong></td>
                    <td><?= htmlspecialchars($request['firstname_admin']); ?></td>
                </tr>
                <tr>
                    <td><strong>Nom Admin :</strong></td>
                    <td><?= htmlspecialchars($request['lastname_admin']); ?></td>
                </tr>
                <tr>
                    <td><strong>Email Admin :</strong></td>
                    <td><?= htmlspecialchars($request['mail_admin']); ?></td>
                </tr>
                <tr>
                    <td><strong>CGU :</strong></td>
                    <td><?= htmlspecialchars($request['cgu']); ?></td>
                </tr>
            </table>
            <div class="cards-container">
                <div class="card-session">
                    <h3><?= htmlspecialchars($request['firstname_establishment']); ?></h3><br>
                    <p><strong>ID :</strong></p>
                    <p><?= htmlspecialchars($request['id']); ?></p>
                    <p><strong>Adresse :</strong></td>
                    <p><?= htmlspecialchars($request['adresse']); ?></p>
                    <p><strong>Téléphone :</strong></p>
                    <p><?= htmlspecialchars($request['phone']); ?></p>
                    <p><strong>Email :</strong></p>
                    <p><?= htmlspecialchars($request['mail']); ?></p>
                    <p><strong>Siret :</strong></p>
                    <p><?= htmlspecialchars($request['siret']); ?></p>
                    <p><strong>Site :</strong></p>
                    <p><?= htmlspecialchars($request['site']); ?></p>
                    <p><strong>Type role :</strong></p>
                    <p><?= htmlspecialchars($request['type_role']); ?></p>
                    <p><strong>Description :</strong></p>
                    <p><?= htmlspecialchars($request['description']); ?></p>
                    <p><strong>Prenom Responsable :</strong></p>
                    <p><?= htmlspecialchars($request['firstname_admin']); ?></p>
                    <p><strong>Nom Responsable :</strong></p>
                    <p><?= htmlspecialchars($request['lastname_admin']); ?></p>
                    <p><strong>Email Responsable :</strong></p>
                    <p><?= htmlspecialchars($request['mail_admin']); ?></p>
                    <p><strong>cgu :</strong></p>
                    <p><?= htmlspecialchars($request['cgu']); ?></p>
                </div>
                <div class="btn-container">
                    <button>
                        <a href="establishment.php" class="btn-back">Retour</a>
                    </button>
                </div>
            </div> 
        </div>
    </main>
 
</body>
</html>



