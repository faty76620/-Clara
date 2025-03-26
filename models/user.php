<?php
// CRÉER UN ADMIN (manager)
function createAdmin($conn, $data) {
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, firstname, lastname, mail, password, establishment_id, role_id, must_change_password, date_create, date_modify) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW(), NULL)");

        return $stmt->execute([
            $data['username'],
            $data['firstname_admin'], 
            $data['lastname_admin'], 
            $data['mail_admin'], 
            $data['password'], 
            $data['establishment_id'], 
            2
        ]);
    } catch (PDOException $e) {
        error_log("Erreur lors de la création de l'admin : " . $e->getMessage());
        return false;
    }
}


// CRÉER UN UTILISATEUR (soignant)
function createUser($conn, $data) {
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, firstname, lastname, mail, password, establishment_id, role_id, must_change_password, date_create, date_modify) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW(), NULL)");

        return $stmt->execute([
            $data['username'],
            $data['firstname_user'], 
            $data['lastname_user'], 
            $data['mail_user'], 
            $data['password'], 
            $data['establishment_id'], 
            3 // Le rôle d'un utilisateur
        ]);
    } catch (PDOException $e) {
        error_log("Erreur lors de la création de l'utilisateur : " . $e->getMessage());
        return false;
    }
}

// VERIFIER SI UN MANAGER EXISTE DEJA POUR ETABLISSEMENT
function checkManagerExists($conn, $establishment_id) {
    try {
        $sql = "SELECT COUNT(*) FROM users WHERE role_id = 2 AND establishment_id = :establishment_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':establishment_id', $establishment_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Erreur lors de la vérification du manager : " . $e->getMessage());
        return false;
    }
}

// VERIFIER L'EXISTENCE D'UN IDENTIFIANT
function checkUsernameExists($conn, $username) {
    try {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Erreur lors de la vérification du username : " . $e->getMessage());
        return false;
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
        return "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
    }
}


// RECUPERER L'UTILISATEUR PAR SON ID
function getUserById($conn, $id) {
    try {
        $stmt = $conn->prepare("
            SELECT u.id, u.username, u.password, u.date_create, must_change_password, 
                   u.firstname, u.lastname, u.mail, 
                   u.establishment_id, e.firstname AS establishment_name,
                   u.role_id, r.nom AS role_name
            FROM users u
            LEFT JOIN establishments e ON u.establishment_id = e.id
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE u.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        return false;
    }
}

// METTRE A JOUR LE MOT DE PASSE
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

// VERIFIER SI L'EMAIL EXISTE
function checkEmailExists($email, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT id, username FROM users WHERE mail = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Erreur lors de la vérification de l'email : " . $e->getMessage());
        return false;
    }
}

// CREATION D'UN TOKEN
function createPasswordResetToken($userId, $pdo) {
    try {
        $token = bin2hex(random_bytes(16));
        $expiry = time() + 3600;

        $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (:user_id, :token, :expiry)");
        $stmt->execute(['user_id' => $userId, 'token' => $token, 'expiry' => $expiry]);

        return $token;
    } catch (PDOException $e) {
        error_log("Erreur lors de la création du token : " . $e->getMessage());
        return false;
    }
}

// SUPPRIMER UN TOKEN
function deleteToken($token, $pdo) {
    try {
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = :token");
        return $stmt->execute(['token' => $token]);
    } catch (PDOException $e) {
        error_log("Erreur lors de la suppression du token : " . $e->getMessage());
        return false;
    }
}

// METTRE A JOUR UN UTILISATEUR
function updateUser($conn, $id, $firstname, $lastname, $mail, $establishment_id, $role_id) {
    try {
        $stmt = $conn->prepare("UPDATE users 
            SET firstname = ?, lastname = ?, mail = ?, establishment_id = ?, role_id = ? 
            WHERE id = ?");
        return $stmt->execute([$firstname, $lastname, $mail, $establishment_id, $role_id, $id]);
    } catch (Exception $e) {
        error_log("Erreur mise à jour utilisateur : " . $e->getMessage());
        return false;
    }
}

// RECUPERER TOUT LES ROLE
function getAllRoles($conn) {
    try {
        $stmt = $conn->prepare("SELECT * FROM roles");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération des rôles : " . $e->getMessage());
        return false;
    }
}

// AFFICHER LES UTILISATEURS SELON LE RÔLE 

function getUsersByRole($conn, $role, $search = '') {
    try {
        $searchQuery = $search ? "AND (u.firstname LIKE :search OR u.lastname LIKE :search OR u.mail LIKE :search)" : '';
        $query = "
            SELECT u.id, u.username, u.date_create, u.firstname, u.lastname, u.mail, 
                   e.firstname AS establishment_name, 
                   r.nom AS role_name
            FROM users u
            LEFT JOIN establishments e ON u.establishment_id = e.id
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE r.nom = :role
            $searchQuery
        ";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':role', $role);
        if ($search) {
            $stmt->bindValue(':search', '%' . $search . '%');
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
        return false;
    }
}


// SUPPRIMER UN UTILISATEUR
function deleteUser($pdo, $userId) {
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$userId]);
    } catch (PDOException $e) {
        error_log("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
        return false;
    }
}
?>






