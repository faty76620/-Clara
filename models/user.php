<?php
require_once 'database.php';

// CRÉER UN UTILISATEUR 
function createUser($data) {
    $conn = getConnexion();
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, establishment_id, role_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['username'], 
        $data['password'], 
        $data['email'], 
        $data['establishment_id'], 
        $data['role_id']]);
}

// FONCTION POUR RÉCUPÉRER LES INFORMATIONS D'UN UTILISATEUR À PARTIR DE LA BASE DE DONNÉES EN UTILISANT SON ID
function getUserById($id) {
    $conn = getConnexion();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// FONCTION POUR METTRE À JOUR LES INFORMATIONS D'UN UTILISATEUR EXISTANT DANS LA BASE DE DONNÉES
function updateUser($id, $data) {
    $conn = getConnexion();
    $stmt = $conn->prepare("UPDATE users SET  = ?, firstname = ?, name = ?, email = ?, password = ?, role = ?, WHERE id = ?");
    $stmt->execute([
        $data['firstname'], 
        $data['email'], 
        $data['password'],
        $data['role_id'],
        $id
    ]);
}
?>

