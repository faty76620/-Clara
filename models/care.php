<?php

// AJOUTER UN SOIN
function createCareSession($conn, $data) {
    try {
        // Sécuriser les données
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }

        // Préparer la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO care (patient_id, care_type, care_description, days_of_week, care_hours) 
                                VALUES (?, ?, ?, ?, ?)");

        // Exécuter la requête avec les données sécurisées
        return $stmt->execute([
            $data['patient_id'], 
            $data['care_type'], 
            $data['care_description'], 
            $data['days_of_week'],
            $data['care_hours']
        ]);
    } catch (Exception $e) {
        error_log("Erreur lors de l'insertion du soin : " . $e->getMessage());
        return false;
    }
}


// RECUPERER LES SOINS
function getCareByPatient($conn, $patient_id) {
    try {
        $sql = "SELECT * FROM care WHERE patient_id = :patient_id ORDER BY days_of_week DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['patient_id' => $patient_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur récupération soins: " . $e->getMessage());
        return false;
    }
}

?>