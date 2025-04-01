<?php
// INSERTION DANS LA TABLE VITAL_SIGNS
function createVitalSigns($conn, $data) {
    try {
        // Sécuriser les données
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }

        // Préparer la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO vital_signs (patient_id, temperature, blood_pressure, heart_rate, respiratory_rate, recorded_at) 
                                VALUES (?, ?, ?, ?, ?, ?)");

        // Exécuter la requête avec les données sécurisées
        return $stmt->execute([
            $data['patient_id'], 
            $data['temperature'], 
            $data['blood_pressure'], 
            $data['heart_rate'],
            $data['respiratory_rate'],
            $data['recorded_at']
        ]);
    } catch (Exception $e) {
        error_log("Erreur lors de l'insertion des constantes vitales : " . $e->getMessage());
        return false;
    }
}

// RECUPERER LES CONSTANTES VITALES
function getVitalSignsByPatient($conn, $patient_id) {
    try {
        $sql = "SELECT * FROM vital_signs WHERE patient_id = :patient_id ORDER BY recorded_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['patient_id' => $patient_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur récupération constantes vitales: " . $e->getMessage());
        return false;
    }
}

// METTRE À JOUR LES CONSTANTES VITALES
function updateVitalSigns($conn, $id, $temperature, $heart_rate, $respiratory_rate) {
    try {
        $sql = "UPDATE vital_signs SET temperature = :temperature, heart_rate = :heart_rate, respiratory_rate = :respiratory_rate WHERE vital_sign_id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute(compact('temperature', 'heart_rate', 'respiratory_rate', 'id'));
    } catch (PDOException $e) {
        error_log("Erreur update vital signs: " . $e->getMessage());
        return false;
    }
}

function getVitalSignsById($conn, $vital_sign_id) {
    try {
        $sql = "SELECT * FROM vital_signs WHERE id = :vital_sign_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['vital_sign_id' => $vital_sign_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur récupération des constantes vitales: " . $e->getMessage());
        return false;
    }
}

?>