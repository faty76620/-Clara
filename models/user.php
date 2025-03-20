
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

// RECUPERER L'UTILISATEUR PAR SON IDENTIFIANT 
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

// RECUPERER L'UTILISATEUR PAR SON ID
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

//METTRE A JOUR LE MOT DE PASSE A LA PREMIERE CONNEXION
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

class PasswordResetModel {
    // VERIFIER SI L'EMAIL EXISTE
    public function checkEmailExists($email, $pdo) {
        $sql = "SELECT id, username FROM users WHERE mail = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    // CREATION D'UN TOKEN
    public function createPasswordResetToken($userId, $pdo) {
        $token = bin2hex(random_bytes(16)); // Génère un token sécurisé
        $expiry = time() + 3600; // Expiration dans 1 heure
    
        $sql = "INSERT INTO password_resets (user_id, token, expiry) VALUES (:user_id, :token, :expiry)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'token' => $token, 'expiry' => $expiry]);
    
        return $token;
    }
    
    // VERIFICATION DE LA VALIDATIONDU TOKEN
    public function verifyToken($token, $pdo) {
        $sql = "SELECT * FROM password_resets WHERE token = :token AND expiry > :current_time";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['token' => $token, 'current_time' => time()]);
        return $stmt->fetch();
    }
    
    // MISE A JOUR DU MOT DE PASSE DANS USERS 'password'
    public function updatePassword($userId, $newPassword, $pdo) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :password WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['password' => $hashedPassword, 'user_id' => $userId]);
    }
    
    // SUPPRESSION DU TOKEN
    public function deleteToken($token, $pdo) {
        $sql = "DELETE FROM password_resets WHERE token = :token";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['token' => $token]);
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



