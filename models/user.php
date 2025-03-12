<?php
require_once 'database.php';

// CRÉER UN UTILISATEUR
function createUser($data) {
    $conn = getConnexion();
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

// RECUPERER UN UTILISATEUR PAR UN ID
 function getUserByUsername($username) { 
    $conn = getConnexion();
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?"); 
    $stmt->execute([$username]); return 
    $stmt->fetch(PDO::FETCH_ASSOC); 
} 

function getUserById($user_id) {
    $conn = getConnexion();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//METTRE A JOUR LE MOT DE PASSE METTRE LE NOUVEAU MOT DE PASSE
function updatePassword($user_id, $new_password) {
    $conn = getConnexion();
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET password = ?, must_change_password = 0 WHERE id = ?");
    return $stmt->execute([$hashed_password, $user_id]);
}
    //ASSURER AVOIR UN ADMIN
    $conn = getConnexion();
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role_id = 1");
    $stmt->execute();
    $adminExists = $stmt->fetchColumn();

    if ($adminExists > 0) {
    echo "Un administrateur existe déjà.";
    } else {
    // Création de l'admin
}

?>



