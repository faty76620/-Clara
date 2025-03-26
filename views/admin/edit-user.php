<?php
// Inclure les fichiers nécessaires
include __DIR__ . '/../../templates/session_start.php';
require_once '../../models/database.php';
require_once '../../models/user.php';
require_once '../../models/establishment.php';

// Connexion à la base de données
$conn = getConnexion();

// Vérifier si un ID est passé
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID utilisateur invalide !";
    header("Location: user.php");
    exit();
}

$id = intval($_GET['id']);
$user = getUserById($conn, $id);

if (!$user) {
    $_SESSION['error'] = "Utilisateur non trouvé !";
    header("Location: user.php");
    exit();
}

// Récupérer les établissements et les rôles pour les options de sélection
$establishments = getAllEstablishments($conn);
$roles = getAllRoles($conn);
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
    <title>Modifier l'utilisateur</title>
</head>   
<body class="body-background">
<?php include __DIR__ . '/../../templates/header_admin.php'; ?>
    <main class="dashboard">
        <div class="container-title"><h2>Modifier l'utilisateur</h2></div>
        <div class="card">
            <form method="POST" action="../../controllers/edit-user.php" class="form-session">
                <!-- ID caché pour référence -->
                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <div class="group-form">
                    <label>Nom :</label>
                    <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>
                </div>
                <div class="group-form">
                    <label>Prénom :</label>
                    <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required>
                </div>
                <div class="group-form">
                    <label>Email :</label>
                    <input type="email" name="mail" value="<?= htmlspecialchars($user['mail']) ?>" required>
                </div>
                <div class="group-form">
                    <label>Etablissement :</label>
                    <select name="establishment_id" required>
                        <?php foreach ($establishments as $establishment): ?>
                            <option value="<?= $establishment['id'] ?>" 
                                <?= $establishment['id'] == $user['establishment_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($establishment['firstname']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="group-form">
                    <label>Rôle :</label>
                    <select name="role_id" required>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" 
                                <?= $role['id'] == $user['role_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($role['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="btn-container">
                    <button type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
