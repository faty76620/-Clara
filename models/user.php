
<?php
// CRÉER UN ADMIN (manager)
function createAdmin($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO users (username, firstname, lastname, mail, password, establishment_id, role_id, must_change_password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 1)");

    return $stmt->execute([
        $data['username'],
        $data['firstname_admin'], 
        $data['lastname_admin'], 
        $data['mail_admin'], 
        $data['password'], 
        $data['establishment_id'], 
        htmlspecialchars($data['role'])
    ]);
}

// CRÉER UN UTILISATEUR 
function createUser($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO users (username, firstname, lastname, mail, password, establishment_id, role_id, must_change_password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 1)"); 

    return $stmt->execute([
        $data['username'],
        $data['firstname_user'], 
        $data['lastname_user'], 
        $data['mail_user'], 
        $data['password'], 
        $data['establishment_id'], 
        $data['role_user'] 
    ]);
}

// VERIFIER L'EXISTENCE D'UN IDENTIFIANT
function checkUsernameExists($conn, $username) {
    $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

//RECUPERER UN UTILISATEUR PAR SON ID
function getUserById($conn, $user_id) {
    $sql = "SELECT * FROM users WHERE id = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Erreur lors de la récupération de l'ID : " . $e->getMessage();
        return false;
    }
}

// RECUPERER UN UTILISATEUR PAR SON IDENTIFIANT
function getUserByUsername($conn, $username) {
    try {
        $sql = "SELECT id, username, password, role_id, must_change_password, lastname FROM users WHERE username = :username LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user ?: false; 
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        return false; 
    }
}

// MISE A JOUR DU MOT DE PASSE
function updateUserPassword($conn, $user_id, $new_password) {
    try {
        // Hachage sécurisé du mot de passe
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        if (!$hashed_password) {
            throw new Exception("Erreur lors du hachage du mot de passe.");
        }

        $sql = "UPDATE users SET password = :password, must_change_password = 0 WHERE id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        
        return $stmt->execute();
    } catch (Exception $e) {
        error_log("Erreur lors de la mise à jour du mot de passe : " . $e->getMessage());
        return false;
    }
}

// FONCTION POUR VERIFIER SI UN MANAGER PAR ETABLISSEMENT  
function checkManagerExists($conn, $establishment_id) {
    // Requête pour vérifier s'il existe déjà un manager pour l'établissement
    $sql = "SELECT COUNT(*) FROM users WHERE role_id = 2 AND establishment_id = :establishment_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':establishment_id', $establishment_id, PDO::PARAM_INT);
    $stmt->execute();
    // Si le compteur est supérieur à 0, un manager existe déjà pour cet établissement
    return $stmt->fetchColumn() > 0;
}



?>



