<?php
// RÉCUPÉRATION DES PLANNINGS
function getSchedules($conn, $view, $date) {
    // Correction du nom de la table 'patients' au lieu de 'patient'
    $sql = "SELECT schedule.*, users.firstname AS user_firstname, users.lastname AS user_lastname, 
                   patients.firstname AS patient_firstname, patients.lastname AS patient_lastname
            FROM schedule 
            JOIN users ON schedule.user_id = users.id
            JOIN patients ON schedule.patient_id = patients.patient_id";

    // Filtrage selon la vue
    if ($view == 'day') {
        $sql .= " WHERE DATE(schedule.start_datetime) = :date";
    } elseif ($view == 'week') {
        $sql .= " WHERE YEARWEEK(schedule.start_datetime, 1) = YEARWEEK(:date, 1)";
    } elseif ($view == 'month') {
        $sql .= " WHERE MONTH(schedule.start_datetime) = MONTH(:date) AND YEAR(schedule.start_datetime) = YEAR(:date)";
    }

    // Préparation et exécution de la requête
    $stmt = $conn->prepare($sql);
    $stmt->execute(['date' => $date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// AJOUTER UN PLANNING
function addSchedule($conn, $user_id, $patient_id, $start_datetime, $end_datetime) {
    $sql = "INSERT INTO schedule (user_id, patient_id, start_datetime, end_datetime)
            VALUES (:user_id, :patient_id, :start_datetime, :end_datetime)";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        'user_id' => $user_id,
        'patient_id' => $patient_id,
        'start_datetime' => $start_datetime,
        'end_datetime' => $end_datetime
    ]);
}

// MODIFIER UN PLANNING
function updateSchedule($conn, $schedule_id, $start_datetime, $end_datetime) {
    $sql = "UPDATE schedule SET start_datetime = :start_datetime, end_datetime = :end_datetime WHERE id = :schedule_id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        'schedule_id' => $schedule_id,
        'start_datetime' => $start_datetime,
        'end_datetime' => $end_datetime
    ]);
}

// SUPPRIMER UN PLANNING
function deleteSchedule($conn, $schedule_id) {
    $sql = "DELETE FROM schedule WHERE id = :schedule_id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute(['schedule_id' => $schedule_id]);
}

?>
