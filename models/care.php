<?php
// FONCTION POUR CRÉER UN NOUVEAU SOIN
function createCareSession($conn, $data) {
    try {
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }

        $stmt = $conn->prepare("INSERT INTO care (patient_id, care_type, care_description, days_of_week, time_slot, categorie, frequence,
                                            designed_caregiver, date_modified, care_start_date, care_end_date) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)");

        return $stmt->execute([
            $data['patient_id'], 
            $data['care_type'], 
            $data['care_description'],
            $data['days_of_week'],
            $data['time_slot'],
            $data['categorie'],
            $data['frequence'],
            $data['designed_caregiver'],
            $data['care_start_date'],   
            $data['care_end_date'],     
        ]);
    } catch (Exception $e) {
        error_log("Erreur lors de l'insertion du soin : " . $e->getMessage());
        return false;
    }
}


// FONCTION POUR RÉCUPÉRER UN SOIN PAR SON ID
function getCareById($conn, $care_id) {
    try {
        $sql = "SELECT * FROM care WHERE care_id = :care_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['care_id' => $care_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur récupération soin : " . $e->getMessage());
        return false;
    }
}

// RECUPERER LES SOINS DU PATIENT DANS LA PAGE DE MODIFICATION 
function getCareByPatient($conn, $patient_id) {
    try {
        $sql = "SELECT * FROM care WHERE patient_id = :patient_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['patient_id' => $patient_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur récupération soins: " . $e->getMessage());
        return false;
    }
}

// FONCTION POUR RÉCUPÉRER LES SOINS D'UN PATIENT AVEC LES INFOS DU SOIGNANT
function getCareByPatientWithCaregiver($conn, $patient_id) {
    try {
        $sql = "
            SELECT c.care_id, c.care_type, c.care_description, c.care_start_date, c.care_end_date, c.days_of_week, c.time_slot, 
            c.categorie, c.frequence, c.date_modified,
            u.firstname AS caregiver_firstname, u.lastname AS caregiver_lastname

            FROM care c
            JOIN users u ON c.designed_caregiver = u.id 
            WHERE c.patient_id = ?  
            ORDER BY c.date_modified DESC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$patient_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    } catch (Exception $e) {
        error_log("Erreur récupération soins + soignants : " . $e->getMessage());
        return false; 
    }
}

// FONCTION POUR METTRE À JOUR UN SOIN
function updateCare($conn, $id, $user_id, $care_type, $care_description, $care_start_date, $care_end_date, $care_date, $categorie, $frequence, $days_of_week, $time_slot, $designed_caregiver) {
    try {
        $sql = "UPDATE care 
                SET user_id = :user_id,
                    care_type = :care_type, 
                    care_description = :care_description, 
                    care_start_date = :care_start_date, 
                    care_end_date = :care_end_date, 
                    care_date = :care_date,
                    categorie = :categorie, 
                    frequence = :frequence,
                    days_of_week = :days,
                    time_slot = :time_slot,
                    designed_caregiver = :designed_caregiver,
                    date_modified = NOW()
                WHERE care_id = :id";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            'user_id' => $user_id,
            'care_type' => $care_type,
            'care_description' => $care_description,
            'care_start_date' => $care_start_date,   
            'care_end_date' => $care_end_date, 
            'care_date' => $care_date,
            'categorie' => $categorie,
            'frequence' => $frequence,
            'days' => $days_of_week,
            'time_slot' => $time_slot,
            'designed_caregiver' => $designed_caregiver,
            'id' => $id
        ]);
    } catch (PDOException $e) {
        error_log("Erreur update care : " . $e->getMessage());
        return false;
    }
}


?>
