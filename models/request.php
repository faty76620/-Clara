<?php
require_once 'database.php';
//CREER LA REQUETE
function createRequest($data) {
    $conn = getConnexion();

    // Sécuriser les données
    foreach ($data as $key => $value) {
        $data[$key] = htmlspecialchars(trim($value));
    }

    $stmt = $conn->prepare("INSERT INTO requests (firstname_admin, lastname_admin, role, mail_admin, firstname_establishment, adresse, type_role, siret, mail, phone, site, description, cgu, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'en attente')");

    return $stmt->execute([
        $data['firstname_admin'], 
        $data['lastname_admin'], 
        $data['role'], 
        $data['mail_admin'],
        $data['firstname_establishment'], 
        $data['adresse'], 
        $data['type_role'], 
        $data['siret'],
        $data['mail'], 
        $data['phone'], 
        $data['site'], 
        $data['description'], 
        $data['cgu'], 
    ]);
} 

// RÉCUPÉRER UNE DEMANDE PAR ID
function getRequestById($id) {
    $conn = getConnexion();
    $stmt = $conn->prepare("SELECT * FROM requests WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// METTRE À JOUR LE STATUT D'UNE DEMANDE
function updateRequestStatus($id, $status) {
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        return true;
    } catch (Exception $e) {
        error_log("Erreur lors de la mise à jour du statut : " . $e->getMessage());
        return false;
    }
}

// RÉCUPÉRER LES DEMANDES EN ATTENTE
function getPendingRequests() {
    $conn = getConnexion();
    $stmt = $conn->prepare("SELECT * FROM requests WHERE status = 'en attente' ORDER BY id DESC"); 
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>





