<?php
function getAllPlannings($conn) {
    $sql = "SELECT * FROM schedules ORDER BY start_datetime";
    return $conn->query($sql)->fetchAll();
}

function getPlanningsByUser($conn, $userId) {
    $sql = "SELECT * FROM schedules WHERE user_id = :user_id ORDER BY start_datetime";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

function getPlanningById($conn, $id) {
    $sql = "SELECT * FROM schedules WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}


// Fonction pour ajouter une planification dans la table schedules
function addPlanning($conn, $user_id, $patient_id, $care_id, $start_datetime, $end_datetime, $planning_date) {
    try {
        // Sécurisation des données
        $user_id = (int) $user_id;
        $patient_id = (int) $patient_id;
        $care_id = (int) $care_id;
        $start_datetime = htmlspecialchars(trim($start_datetime));
        $end_datetime = htmlspecialchars(trim($end_datetime));
        $planning_date = htmlspecialchars(trim($planning_date));

        // Insertion dans la table schedules
        $stmt = $conn->prepare("INSERT INTO schedules (user_id, patient_id, start_datetime, end_datetime, care_id, planning_date) 
                                 VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $patient_id, $start_datetime, $end_datetime, $care_id, $planning_date]);

        // Retourner true si l'insertion a réussi
        return true;
    } catch (PDOException $e) {
        error_log("Erreur lors de l'ajout de la planification : " . $e->getMessage());
        return false;
    }
}

function updatePlanning($conn, $data) {
    $sql = "UPDATE schedules SET user_id = :user_id, patient_id = :patient_id, start_datetime = :start_datetime, end_datetime = :end_datetime WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute($data);
}

function deletePlanning($conn, $id) {
    $sql = "DELETE FROM schedules WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute(['id' => $id]);
}


?>
