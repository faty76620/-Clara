<?php
function createCaregiver($conn, $data) {
    try {
        // Sécuriser les données
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }

        // Requête préparée
        $stmt = $conn->prepare("INSERT INTO caregiver
            (user_id, specialite, diplome, experience, competences) 
            VALUES (?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['user_id'], 
            $data['specialite'], 
            $data['diplome'], 
            $data['experience'], 
            $data['competences']
        ]);

        // Vérifier si l'insertion a réussi
        $lastId = $conn->lastInsertId();
        if ($lastId) {
            return $lastId;
        } else {
            throw new Exception("Erreur lors de l'insertion du soignant.");
        }
    } catch (PDOException $e) {
        error_log("Erreur : " . $e->getMessage());
        return false;
    }
}


// FONCTION QUI PERMET DE CHERCHER LE SOIGNAT ET RECUPERER SES INFOS
function getCaregivers($conn, $search = '') {
    try {
        $query = "SELECT caregiver.*, users.firstname, users.lastname, e.firstname AS establishment_name
                  FROM caregiver 
                  JOIN users ON caregiver.user_id = users.id 
                  LEFT JOIN establishments e ON users.establishment_id = e.id";  // Jointure ajoutée pour récupérer le nom de l'établissement
                  
        if (!empty($search)) {
            $query .= " WHERE users.firstname LIKE :search 
                        OR users.lastname LIKE :search 
                        OR caregiver.specialite LIKE :search 
                        OR caregiver.diplome LIKE :search
                        OR e.firstname LIKE :search";  
        }

        $query .= " ORDER BY caregiver.id DESC";
        $stmt = $conn->prepare($query);

        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération des soignants : " . $e->getMessage());
        return false;
    }
}


//FONCTION POUR AFFICHIER DETAILS INFOS RECUPERER PAR ID
function getCaregiverById($conn, $id) {
    try {
        $stmt = $conn->prepare("
            SELECT 
                caregiver.*, 
                users.firstname, 
                users.lastname, 
                users.establishment_id,  -- Ajout de l'ID de l'établissement
                e.firstname AS establishment_name,  -- Ajout du nom de l'établissement
                users.mail,
                users.date_create,
                caregiver.id,
                caregiver.specialite, 
                caregiver.diplome, 
                caregiver.competences,
                caregiver.experience
               
            FROM caregiver
            JOIN users ON caregiver.user_id = users.id
            LEFT JOIN establishments e ON users.establishment_id = e.id  -- Jointure pour récupérer les infos de l'établissement
            WHERE caregiver.user_id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur PDO : " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        error_log("Erreur : " . $e->getMessage());
        return false;
    }
}

// FONCTION POUR METTRE A JOUR LES INFOS PRO DU SOIGNANT 
function updateCaregiver($conn, $caregiver_id, $specialite, $diplome, $experience, $competences) {
    try {
        $query = "UPDATE caregiver SET 
                    specialite = :specialite, 
                    diplome = :diplome, 
                    experience = :experience, 
                    competences = :competences
                  WHERE id = :caregiver_id";
        
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':specialite', $specialite);
        $stmt->bindValue(':diplome', $diplome);
        $stmt->bindValue(':experience', $experience, PDO::PARAM_INT);
        $stmt->bindValue(':competences', $competences);
        $stmt->bindValue(':caregiver_id', $caregiver_id, PDO::PARAM_INT);

        return $stmt->execute(); 
    } catch (PDOException $e) {
        error_log("Erreur PDO lors de la mise à jour du soignant : " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        error_log("Erreur lors de la mise à jour du soignant : " . $e->getMessage());
        return false;
    }
}


// FONCTION POUR METTRE A JOUR LES INFO PERSO DU SOIGNANT
function updateCaregiverPerso($conn, $user_id, $firstname, $lastname, $mail, $establishment_id) {
    try {
        $query = "
            UPDATE users 
            SET 
                firstname = :firstname,
                lastname = :lastname,
                mail = :mail,
                establishment_id = :establishment_id
            WHERE id = :user_id
        ";
        $stmt = $conn->prepare($query);

        // Lier les paramètres
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
        $stmt->bindParam(':establishment_id', $establishment_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        // Exécution de la requête
        $stmt->execute();

        // Vérifier si la mise à jour a bien eu lieu
        if ($stmt->rowCount() > 0) {
            return true;  // Mise à jour réussie
        } else {
            return false; // Aucune ligne affectée (probablement aucune modification)
        }
    } catch (PDOException $e) {
        // En cas d'erreur de base de données
        error_log("Erreur PDO : " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        // En cas d'erreur générale
        error_log("Erreur : " . $e->getMessage());
        return false;
    }
}

// SUPPRIMER UN SOIGNANT ET SES DONNÉES ASSOCIÉES
function deleteCaregiver($conn, $user_id) {
    try {
        // Suppression du soignant dans la table caregiver
        $sql = "DELETE FROM caregiver WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Suppression de l'utilisateur dans la table users (si nécessaire)
        // Si vous souhaitez également supprimer l'utilisateur lié dans la table users, décommentez la section ci-dessous.
    
        $sqlUser = "DELETE FROM users WHERE id = :user_id";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmtUser->execute();
    

        return true; // Retourne true si la suppression a réussi
    } catch (Exception $e) {
        // Si une erreur survient, retourne false
        error_log("Erreur lors de la suppression du soignant : " . $e->getMessage());
        return false;
    }
}


?>




