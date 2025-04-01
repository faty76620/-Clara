<?php

// CREER UN PATIENT
function createPatient($conn, $data) {
    try {
        // Sécuriser les données
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }

        // Requête préparée
        $stmt = $conn->prepare("INSERT INTO patients 
            (firstname, lastname, email, phone, address, date_of_birth, gender, medical_history, psychological_history, social_history, personal_notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['firstname'], 
            $data['lastname'], 
            $data['email'], 
            $data['phone'],
            $data['address'], 
            $data['date_of_birth'], 
            $data['gender'], 
            $data['medical_history'],
            $data['psychological_history'], 
            $data['social_history'], 
            $data['personal_notes']
        ]);

        // Vérifier si l'insertion a réussi
        $lastId = $conn->lastInsertId();
        if ($lastId) {
            return $lastId;
        } else {
            throw new Exception("Erreur lors de l'insertion du patient.");
        }
    } catch (PDOException $e) {
        error_log("Erreur SQL : " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        error_log("Erreur : " . $e->getMessage());
        return false;
    }
}

// METTRE A JOUR INFORMATIONS DES PATIENTS
function updatePatient($conn, $id, $lastname, $firstname, $dob, $gender, $address, $email, $phone) {
    try {
        $sql = "UPDATE patients SET lastname = :lastname, firstname = :firstname, date_of_birth = :dob, gender = :gender, address = :address, email = :email, phone = :phone WHERE patient_id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute(compact('lastname', 'firstname', 'dob', 'gender', 'address', 'email', 'phone', 'id'));
    } catch (PDOException $e) {
        error_log("Erreur update patient: " . $e->getMessage());
        return false;
    }
}

// RECUPERER UN PATIENT PAR ID
function getPatientById($conn, $patient_id) {
    try {
        $sql = "SELECT * FROM patients WHERE patient_id = :patient_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['patient_id' => $patient_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur récupération patient: " . $e->getMessage());
        return false;
    }
}

// SUPPRIMER UN PATIENT
function deletePatient($conn, $patient_id) {
    try {
        $sql = "DELETE FROM patients WHERE patient_id = :patient_id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute(['patient_id' => $patient_id]);
    } catch (PDOException $e) {
        error_log("Erreur suppression patient: " . $e->getMessage());
        return false;
    }
}

// RECUPER LES INFORMATIONS DU PATIENT (RECHERCHE PAS NOM, PRENOM, DATE DE NAISSANCE)
function getPatients($conn, $search = '') {
    try {
        $query = "SELECT * FROM patients";
        if (!empty($search)) {
            $query .= " WHERE firstname LIKE :search 
                        OR lastname LIKE :search 
                        OR patient_id LIKE :search 
                        OR date_of_birth LIKE :search";
        }
        $query .= " ORDER BY patient_id DESC"; 

        $stmt = $conn->prepare($query);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération des patients : " . $e->getMessage());
        return false;
    }
}
?>

