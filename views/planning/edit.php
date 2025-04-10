<?php

if ($_SESSION['role'] !== 'manager') {
    header('Location: /clara/views/planning/index.php');
    exit;
}

$id = $_GET['id'];
$planning = getPlanningById($conn, $id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => $id,
        'user_id' => $_POST['user_id'],
        'patient_id' => $_POST['patient_id'],
        'start_datetime' => $_POST['start_datetime'],
        'end_datetime' => $_POST['end_datetime'],
    ];
    updatePlanning($conn, $data);
    $_SESSION['success'] = "Planning mis à jour";
    header('Location: index.php');
    exit;
}
?>
// Afficher formulaire pré-rempli
