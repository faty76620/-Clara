<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/planning.php';
require_once MODEL_DIR . '/caregiver.php';
require_once MODEL_DIR . '/patients.php';

$conn = getConnexion();

// Accès manager uniquement
if ($_SESSION['role_id'] != 2){
    header('Location: index.php');
    exit;
}

$users =  getCaregivers($conn, $establishmentId = null); // Tous les soignants de l'etablissement
$patients = getPatients($conn, $establishmentId); // Tous les patients de l'etablissement

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'user_id' => $_POST['user_id'],
        'patient_id' => $_POST['patient_id'],
        'start_datetime' => $_POST['date'] . ' ' . $_POST['start_time'],
        'end_datetime' => $_POST['date'] . ' ' . $_POST['end_time'],
    ];

    if addPlanning($conn, $user_id, $patient_id, $care_id, $start_datetime, $end_datetime, $planning_date) {
        $_SESSION['success'] = "Soin ajouté avec succès !";
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout du soin.";
    }
}
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
    <title>Ajout intervention</title>
</head>
<body>
<?php include TEMPLATE_DIR . '/header_manager.php'; ?>
    <main class="dashboard">
    <div class="container-title"><h2>Ajouter une intervention</h2></div>
        <div class="card">
            <form method="POST" class="form-session">
                <label>Soignant :</label>
                <select name="user_id" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= $user['firstname'] . ' ' . $user['lastname'] ?></option>
                    <?php endforeach; ?>
                </select><br>

                <label>Patient :</label>
                <select name="patient_id" required>
                    <?php foreach ($patients as $patient): ?>
                        <option value="<?= $patient['patient_id'] ?>"><?= $patient['firstname'] . ' ' . $patient['lastname'] ?></option>
                    <?php endforeach; ?>
                </select><br>

                <label>Date :</label>
                <input type="date" name="date" required><br>

                <label>Heure de début :</label>
                <input type="time" name="start_time" required><br>

                <label>Heure de fin :</label>
                <input type="time" name="end_time" required><br>

                <button type="submit">Ajouter</button>
            </form>
        </div>
    </main>
</body>
</html>
