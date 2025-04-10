<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/establishment.php';

$conn = getConnexion();

// Vérifier si un ID utilisateur valide est passé
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID utilisateur invalide !";
    header("Location: " . BASE_URL . "/views/admin/user.php");
    exit();
}

$id = intval($_GET['id']);
$user = getUserById($conn, $id);

if (!$user) {
    $_SESSION['error'] = "Utilisateur non trouvé !";
    header("Location: " . BASE_URL . "/views/admin/user.php");
    exit();
}

// Récupérer les établissements et les rôles
$establishments = getAllEstablishments($conn);
$roles = getAllRoles($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/logo-onglet.png">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="<?= BASE_URL ?>/assets/js.js"></script>
    <title>Modification Soignant</title>
</head>
<body class="body-background">
    <?php include TEMPLATE_DIR . '/header_admin.php'; ?>

    <!-- Affichage des messages -->
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
        <div class="container-title"><h2>Modifier l'utilisateur</h2></div>
        <div class="card">
            <form method="POST" action="<?= BASE_URL ?>/controllers/edit-user.php" class="form-session">
                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

                <?php
                $fields = [
                    "Nom" => ["firstname", "text"],
                    "Prénom" => ["lastname", "text"],
                    "Email" => ["mail", "email"]
                ];
                foreach ($fields as $label => [$name, $type]) :
                ?>
                    <div class="group-form">
                        <label><?= $label ?> :</label>
                        <input type="<?= $type ?>" name="<?= $name ?>" value="<?= htmlspecialchars($user[$name]) ?>" required>
                    </div>
                <?php endforeach; ?>

                <div class="group-form">
                    <label>Établissement :</label>
                    <select name="establishment_id" required>
                        <?php foreach ($establishments as $establishment): ?>
                            <option value="<?= $establishment['id'] ?>" <?= $establishment['id'] == $user['establishment_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($establishment['firstname']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="group-form">
                    <label>Rôle :</label>
                    <select name="role_id" required>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= $role['id'] == $user['role_id'] ? 'selected' : '' ?>>
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