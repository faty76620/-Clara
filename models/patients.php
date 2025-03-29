<?php

// AJOUTER UN PATIENT
function createPatient($conn, $data) {
    try {
        // Sécuriser les données
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }

        // Préparer la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO patients (firstname, lastname, email, phone, address, date_of_birth, gender, medical_history, psychological_history, social_history, personal_notes) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Exécuter la requête avec les données sécurisées
        return $stmt->execute([
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
    } catch (Exception $e) {
        error_log("Erreur lors de l'insertion du patient : " . $e->getMessage());
        return false;
    }
}


// METTRE A JOUR INFORMATIONS DES PATIENTS
function updatePatient($conn, $patient_id, $firstname, $lastname, $email, $phone, $address, $date_of_birth, $gender, $medical_history, $psychological_history, $social_history, $personal_notes) {
    try {
        $sql = "UPDATE patients SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone, address = :address,
                date_of_birth = :date_of_birth, gender = :gender, medical_history = :medical_history, psychological_history = :psychological_history,
                social_history = :social_history, personal_notes = :personal_notes WHERE patient_id = :patient_id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute(compact('patient_id', 'firstname', 'lastname', 'email', 'phone', 'address', 'date_of_birth', 'gender', 'medical_history', 'psychological_history', 'social_history', 'personal_notes'));
    } catch (PDOException $e) {
        error_log("Erreur modification patient: " . $e->getMessage());
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

function getPatientDetails($conn, $patientId) {
    $sql = "SELECT * FROM patients WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

?>

