<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/caregiver.php';
require_once MODEL_DIR . '/establishment.php';
require_once MODEL_DIR . '/logs.php';

$conn = getConnexion();

if (!isset($_SESSION['establishment_id'])) {
    $_SESSION['error'] = "Aucune information sur l'établissement. Veuillez vous reconnecter.";
    header('Location: login.php'); // Redirigez vers la page de connexion ou une autre page appropriée.
    exit;
}
$establishment_id = $_SESSION['establishment_id'];


// Récupérer l'ID de l'établissement du manager connecté
$establishment_id = $_SESSION['establishment_id'];
// Récupérer les soignants et patients de l'établissement
$users = getCaregiversByEstablishment($conn, $establishment_id);
$patients = getPatientsByEstablishment($conn, $establishment_id);

// Gérer la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $patient_id = $_POST['patient_id'];
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];

    // Validation des champs
    if (empty($user_id) || empty($patient_id) || empty($start_datetime) || empty($end_datetime)) {
        $_SESSION['error'] = "Tous les champs doivent être remplis.";
    } elseif (strtotime($start_datetime) >= strtotime($end_datetime)) {
        $_SESSION['error'] = "L'heure de début doit être avant l'heure de fin.";
    } else {
        // Ajouter le planning
        if (addSchedule($conn, $user_id, $patient_id, $start_datetime, $end_datetime)) {
            $_SESSION['success'] = "Planning ajouté avec succès !";
            header('Location: manage-planning.php');
            exit;
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout du planning.";
        }
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
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/15ca748f1e.js" crossorigin="anonymous"></script>
    <script defer src="/clara/assets/js.js"></script>
    <title>Ajout planning</title>
</head>
<body>
    <?php include TEMPLATE_DIR . '/header_manager.php'; ?>
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
       <div class="container-title"><h2>Ajouter un Planning</h2></div>

        <?php if (isset($_SESSION['success'])): ?>
            <div style="color: green;"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div style="color: red;"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Formulaire d'ajout -->
        <form method="POST" action="add_planning.php" class="form-session">
            <label for="user_id">Soignant :</label>
            <select name="user_id" required>
                <option value="">Sélectionnez un soignant</option>
                <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="patient_id">Patient :</label>
            <select name="patient_id" required>
                <option value="">Sélectionnez un patient</option>
                <?php foreach ($patients as $patient): ?>
                <option value="<?= $patient['patient_id'] ?>"><?= htmlspecialchars($patient['firstname'] . ' ' . $patient['lastname']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="start_datetime">Début :</label>
            <input type="datetime-local" name="start_datetime" required>

            <label for="end_datetime">Fin :</label>
            <input type="datetime-local" name="end_datetime" required>

            <button type="submit">Ajouter</button>
        </form>
    </main>

</body>
</html>


