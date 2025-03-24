<?php
require_once '../../models/database.php';
require_once '../../models/user.php';

$conn = getConnexion();

// Vérifier si un ID est passé en URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID invalide !");
}

$id = intval($_GET['id']);
$user =  getUserById($conn, $id);

if (!$user) {
    die("Utilisateur non trouvé !");
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $mail = $_POST['mail'];
    $establishment_id = $_POST['estasblishment_id'];
    $role_id = $_POST['role_id'];
    if ( updateUser($conn, $id, $firstname, $lastname, $adresse, $mail, $establishment_id, $role_id)) {
        header("Location: users.php?success=1");
        exit();
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
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
    <title>Modifier l'utlisateur</title>
</head>   
<body class="body-background">
<?php include __DIR__ . '/../../templates/header_admin.php';?>
    <main class="dashboard">
        <div class="container-title"><h2>Modifier l'utilisateur</h2></div>
        <div class="card">
            <form method="POST">
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
                    <input type="text" value="<?= htmlspecialchars($user['establishment_name']) ?>" required>
                </div>
                <div class="group-form">
                    <label>Rôle :</label>
                    <input type="text" value="<?= htmlspecialchars($user['role_name']) ?>" reaquired>
                </div>
            
                <button type="submit">Mettre à jour</button>
            </form>
        </div>
        <div class="btn-container">
            <button><a href="users.php">Retour</a></button>
        </div>
    </main>
</body>
</html>