<?php
require_once 'database.php';

// FONCTION POUR CRÉER UNE DEMANDE
function createRequest($data) {
    $conn = getConnexion();
    $stmt = $conn->prepare("
    INSERT INTO requests ( firstname_admin, name_admin, role, mail_admin, firstname_establishment, adresse, type_role, siret, mail, phone, site, description, cgu, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'en attente')
");

    $stmt->execute([
        $data['firstname_admin'], $data['name_admin'], $data['role'], $data['mail_admin'],
        $data['firstname_establishment'], $data['adresse'], $data['type_role'], $data['siret'],
        $data['mail'], $data['phone'], $data['site'], $data['description'], $data['cgu']
    ]);
}

// FONCTION POUR CRÉER UN UTILISATEUR (en tant que nouvel utilisateur)
function createUser($userData) {
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("
        INSERT INTO users (username, password, email, establishment_id, role_id)
        VALUES (?, ?, ?, ?, ?)
    ");

        $stmt->execute([
            $userData['username'], $userData['password'], $userData['email'],
            $userData['establishment_id'], $userData['role_id']
        ]);
    } catch (PDOException $e) {
        error_log("Erreur lors de la création de l'utilisateur : " . $e->getMessage());
        return false;
    }
}

// FONCTION POUR RÉCUPÉRER UNE DEMANDE PAR ID
function getRequestById($id) {
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("SELECT * FROM requests WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération de la demande : " . $e->getMessage());
        return false;
    }
}

// FONCTION POUR METTRE À JOUR LE STATUT D'UNE DEMANDE (ATTENTE OU PAS)
function updateRequestStatus($id, $status) {
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        return true; // INDICATION QUE L'UPDATE A RÉUSSI
    } catch (PDOException $e) {
        error_log("Erreur lors de la mise à jour du statut : " . $e->getMessage());
        return false;
    }
}

// FONCTION RÉCUPÉRER LES DEMANDES EN ATTENTE POUR QUE L'ADMIN PRINCIPAL LES VOIT
function getPendingRequests() {
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("SELECT * FROM requests WHERE status = 'en attente'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des demandes en attente : " . $e->getMessage());
        return [];
    }
}
?>





