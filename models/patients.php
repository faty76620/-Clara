<?php
// CREER UN PATIENT
function createPatient($conn, $data) {
    try {
        // Nettoyage des données
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }

        if (empty($data['establishment_id'])) {
            throw new Exception("L'ID de l'établissement est requis.");
        }

        $stmt = $conn->prepare("INSERT INTO patients (
            firstname, lastname, email, phone, address, etage_appartement, acces_domicile, animaux, 
            contact_urgence_nom, contact_urgence_lien, contact_urgence_tel, date_of_birth, gender, 
            medical_history, psychological_history, social_history, radiologie, radiologie_liste, 
            medecin_traitant, personal_notes, establishment_id, date_modified) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)");
        
        $stmt->execute([
            $data['firstname'],
            $data['lastname'],
            $data['email'],
            $data['phone'],
            $data['address'],
            $data['etage_appartement'],
            $data['acces_domicile'],
            $data['animaux'],
            $data['contact_urgence_nom'],
            $data['contact_urgence_lien'],
            $data['contact_urgence_tel'],
            $data['date_of_birth'],
            $data['gender'],
            $data['medical_history'],
            $data['psychological_history'],
            $data['social_history'],
            $data['radiologie'],
            $data['radiologie_liste'],
            $data['medecin_traitant'],
            $data['personal_notes'],
            $data['establishment_id']
        ]);
        $lastId = $conn->lastInsertId();
        return $lastId ?: false;

    } catch (PDOException $e) {
        error_log("Erreur SQL lors de l'insertion du patient : " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        error_log("Erreur lors de l'insertion du patient : " . $e->getMessage());
        return false;
    }
}

// METTRE A JOUR INFORMATIONS DES PATIENTS
function updatePatient($conn, $id, $lastname, $firstname, $dob, $gender, $address, $email, $phone, $etage, $acces, $animaux, $urgence_nom, $urgence_lien, $urgence_tel, $medical_history, $psychological_history, $social_history, $radiologie, $radiologie_liste, $medecin_traitant, $personal_notes) {
    try {
        $sql = "UPDATE patients 
                SET lastname = :lastname,
                    firstname = :firstname,
                    date_of_birth = :dob,
                    gender = :gender,
                    address = :address,
                    email = :email,
                    phone = :phone,
                    etage_appartement = :etage,
                    acces_domicile = :acces,
                    animaux = :animaux,
                    contact_urgence_nom = :urgence_nom,
                    contact_urgence_lien = :urgence_lien,
                    contact_urgence_tel = :urgence_tel,
                    medical_history = :medical_history,
                    psychological_history = :psychological_history,
                    social_history = :social_history,
                    radiologie = :radiologie,
                    radiologie_liste = :radiologie_liste,
                    medecin_traitant = :medecin_traitant,
                    personal_notes = :personal_notes,
                    date_modified = NOW()
                WHERE patient_id = :id";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            'lastname' => $lastname,
            'firstname' => $firstname,
            'dob' => $dob,
            'gender' => $gender,
            'address' => $address,
            'email' => $email,
            'phone' => $phone,
            'etage' => $etage,
            'acces' => $acces,
            'animaux' => $animaux,
            'urgence_nom' => $urgence_nom,
            'urgence_lien' => $urgence_lien,
            'urgence_tel' => $urgence_tel,
            'medical_history' => $medical_history,
            'psychological_history' => $psychological_history,
            'social_history' => $social_history,
            'radiologie' => $radiologie,
            'radiologie_liste' => $radiologie_liste,
            'medecin_traitant' => $medecin_traitant,
            'personal_notes' => $personal_notes,
            'id' => $id
        ]);
    } catch (PDOException $e) {
        error_log("Erreur SQL lors de la mise à jour du patient : " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        error_log("Erreur lors de la mise à jour du patient : " . $e->getMessage());
        return false;
    }
}


// RECUPERER UN PATIENT PAR ID
function getPatientById($conn, $patient_id) {
    try {
        // Requête avec jointure pour récupérer le nom de l'établissement
        $sql = "SELECT patients.*, establishments.firstname AS establishment_name
                FROM patients
                LEFT JOIN establishments ON patients.establishment_id = establishments.id
                WHERE patients.patient_id = :patient_id";
                
        $stmt = $conn->prepare($sql);
        $stmt->execute(['patient_id' => $patient_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération du patient : " . $e->getMessage());
        return false;
    }
}

// SUPPRIMER UN PATIENT
function deletePatientAndRelatedData($conn, $patient_id) {
    try {
        $sql = "DELETE FROM patients WHERE patient_id = :patient_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_INT);
        $stmt->execute();
        return true; 
    } catch (Exception $e) {
        return false;
    }
}

// AFFICHES TOUTS LES PATIENT DE L'ETABLISSEMENT DU MANAGER CONNECTER
function getPatients($conn, $search = '', $establishmentId = null) {
    try {
        $query = "SELECT patients.*, e.firstname AS establishment_name
                  FROM patients
                  LEFT JOIN establishments e ON patients.establishment_id = e.id";

        if ($establishmentId !== null) {
            $query .= " WHERE patients.establishment_id = :establishmentId";
        }

        if (!empty($search)) {
            if ($establishmentId !== null) {
                $query .= " AND (patients.firstname LIKE :search 
                                OR patients.lastname LIKE :search 
                                OR patients.patient_id LIKE :search 
                                OR patients.date_of_birth LIKE :search 
                                OR e.firstname LIKE :search)";
            } else {
                $query .= " WHERE (patients.firstname LIKE :search 
                                OR patients.lastname LIKE :search 
                                OR patients.patient_id LIKE :search 
                                OR patients.date_of_birth LIKE :search 
                                OR e.firstname LIKE :search)";
            }
        }

        $query .= " ORDER BY patients.patient_id DESC"; 
        $stmt = $conn->prepare($query);

        if ($establishmentId !== null) {
            $stmt->bindValue(':establishmentId', $establishmentId, PDO::PARAM_INT);
        }

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

