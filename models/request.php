<?php
require_once 'database.php';

// FONCTION POUR CRÉER UNE DEMANDE
function createRequest($data) {
    $conn = getConnexion();
    $stmt = $conn->prepare("INSERT INTO requests (firstname_admin, name_admin, role, mail_admin, firstname_establishment, adresse, type_role, siret, mail, phone, site, description, cgu, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'en attente')");
    $stmt->execute([
        $data['firstname_admin'], $data['name_admin'], $data['role'], $data['mail_admin'],
        $data['firstname_establishment'], $data['adresse'], $data['type_role'], $data['siret'],
        $data['mail'], $data['phone'], $data['site'], $data['description'], $data['cgu']
    ]);
}

// FONCTION POUR RÉCUPÉRER UNE DEMANDE PAR ID
function getRequestById($id) {
    $conn = getConnexion();
    $stmt = $conn->prepare("SELECT * FROM requests WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// FONCTION POUR METTRE À JOUR LE STATUT D'UNE DEMANDE (ATTENTE OU PAS)
function updateRequestStatus($id, $status) {
    $conn = getConnexion();
    $stmt = $conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
}

// FONCTION RÉCUPÉRER LES DEMANDES EN ATTENTE POUR QUE L'ADMIN PRINCIPAL LES VOIT
function getPendingRequests() {
    $conn = getConnexion();
    $stmt = $conn->prepare("SELECT * FROM requests WHERE status = 'en attente'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>




