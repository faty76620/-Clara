<?php
require_once 'database.php';

// CRÉER UN UTILISATEUR
function createUser($data) {
    $conn = getConnexion();
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, establishment_id, role_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$data['username'], $data['password'], $data['email'], $data['establishment_id'], $data['role_id']]);
}
?>