<?php
//AJOUTER UN SOINS
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

// METTRE À JOUR LES SOINS
function updateCare($conn, $id, $care_type, $care_description) {
    try {
        $sql = "UPDATE care SET care_type = :care_type, care_description = :care_description WHERE care_id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute(compact('care_type', 'care_description', 'id'));
    } catch (PDOException $e) {
        error_log("Erreur update care: " . $e->getMessage());
        return false;
    }
}

function getCareById($conn, $care_id) {
    try {
        $sql = "SELECT * FROM care WHERE id = :care_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['care_id' => $care_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur récupération soin: " . $e->getMessage());
        return false;
    }
}

function deleteCare($conn, $care_id) {
    try {
        // Préparer la requête de suppression
        $stmt = $conn->prepare("DELETE FROM care_types WHERE id = ?");
        
        // Exécuter la requête
        return $stmt->execute([$care_id]);
    } catch (PDOException $e) {
        error_log("Erreur lors de la suppression du type de soin : " . $e->getMessage());
        return false;
    }
}


?>