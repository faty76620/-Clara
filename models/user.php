
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
    try {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;  // Renvoie true si l'utilisateur existe, sinon false
    } catch (PDOException $e) {
        error_log("Erreur lors de la vérification de l'existence du username : " . $e->getMessage());
        return false;  // Si une erreur survient, on retourne false
    }
}


function getUserByUsername($conn, $username) {
    try {
        $stmt = $conn->prepare("SELECT id, username, password, role_id, firstname, lastname, must_change_password FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return $user;
        } else {
            // Aucun utilisateur trouvé
            return null;
        }
    } catch (PDOException $e) {
        // En cas d'erreur lors de l'exécution de la requête SQL, retourner un message d'erreur
        return "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
    }
}


function getUserById($conn, $user_id) {
    try {
        $query = "SELECT id, username, password, role_id, must_change_password, firstname, lastname, mail FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return $user;
        } else {
            // Aucun utilisateur trouvé avec cet ID
            return null;
        }
    } catch (PDOException $e) {
        // En cas d'erreur lors de l'exécution de la requête SQL, retourner un message d'erreur
        return "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
    }
}


function updateUserPassword($conn, $user_id, $hashed_password) {
    try {
        $sql = "UPDATE users SET password = :password WHERE id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
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



