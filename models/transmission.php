<?php
// INSERTION DANS LA TABLE TRANSMISSIONS
function createTransmission($conn, $data) {
    try {
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }
        $stmt = $conn->prepare("INSERT INTO transmissions (patient_id, transmitted_by, transmission_date, transmission_description, cible) 
                                VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['patient_id'],
            $data['transmitted_by'],
            $data['transmission_date'],
            $data['transmission_description'],
            $data['cible'],
        ]);
    } catch (Exception $e) {
        error_log("Erreur lors de l'insertion de la transmission : " . $e->getMessage());
        return false;
    }
}

// FONCTION POUR RECUPERER TOUTES LES TRANSMISSIONS (JOINTURE AVEC USERS POUR RECUPERER L'UTILISATEUR QUI A CREER LA TRANSMISSION)
function getTransmissionsByPatientWithUser($conn, $patient_id) {
    try {
        $sql = "
            SELECT t.transmission_id, t.transmitted_by, t.transmission_date, t.transmission_description, cible, 
                   u.firstname AS user_firstname, u.lastname AS user_lastname
            FROM transmissions t
            JOIN users u ON t.transmitted_by = u.id
            WHERE t.patient_id = ?
            ORDER BY t.transmission_date DESC"; // Trier par date décroissante
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$patient_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération des transmissions : " . $e->getMessage());
        return false;
    }
}

// METTRE À JOUR LES TRANSMISSIONS 
function updateTransmission($conn, $id, $description) {
    try {
        $sql = "UPDATE transmissions 
                SET transmission_description = :description, 
                    date_modified = NOW() 
                WHERE transmission_id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute(compact('description', 'id'));
    } catch (PDOException $e) {
        error_log("Erreur update transmission: " . $e->getMessage());
        return false;
    }
}

function getTransmissionById($conn, $transmission_id) {
    try {
        $sql = "SELECT * FROM transmissions WHERE transmission_id = :transmission_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['transmission_id' => $transmission_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur récupération transmission: " . $e->getMessage());
        return false;
    }
}

function deleteTransmission($conn, $transmission_id) {
    try {
        // Préparer la requête de suppression
        $stmt = $conn->prepare("DELETE FROM transmissions WHERE id = ?");
        
        // Exécuter la requête
        return $stmt->execute([$transmission_id]);
    } catch (PDOException $e) {
        error_log("Erreur lors de la suppression de la transmission : " . $e->getMessage());
        return false;
    }
}


?>