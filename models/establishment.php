<?php
require_once 'database.php';

// FONCTION POUR CRÉER UN NOUVEL ÉTABLISSEMENT DANS LA BASE DE DONNÉES
function createEstablishment($conn, $request) {
    $stmt = $conn->prepare("INSERT INTO establishments (firstname, adresse, type_role, siret, phone, site, description, mail) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        htmlspecialchars($request['firstname_establishment']), 
        htmlspecialchars($request['adresse']), 
        htmlspecialchars($request['type_role']), 
        htmlspecialchars($request['siret']), 
        htmlspecialchars($request['phone']), 
        htmlspecialchars($request['site']), 
        htmlspecialchars($request['description']), 
        htmlspecialchars($request['mail'])
    ]);

    return $conn->lastInsertId(); // Retourne l'ID du nouvel établissement 
}

// FONCTION POUR RÉCUPÉRER LES INFORMATIONS D'UN ÉTABLISSEMENT À PARTIR DE SON ID
function getEstablishmentById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM establishments WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// FONCTION POUR SUPPRIMER UN ÉTABLISSEMENT EN UTILISANT SON ID
function deleteEstablishment($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM establishments WHERE id = ?");
    $stmt->execute([$id]);
}

// FONCTION POUR RÉCUPÉRER TOUS LES ÉTABLISSEMENTS
function getAllEstablishments($conn) {
    $stmt = $conn->prepare("SELECT * FROM establishments");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// FONCTION POUR RECUPERER LES ETABLISSEMENTS ASSOCIES A DES UTILISATEURS AYANT LE role_id 2 (manager)
function getEstablishmentsFromRole($conn) {
    // Requête SQL pour récupérer l'ID de l'établissement dans la table users pour les utilisateurs ayant role_id = 2
    $sql = "SELECT DISTINCT establishment_id FROM users WHERE role_id = 2"; 
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// FONCTION POUR VÉRIFIER SI UN ÉTABLISSEMENT EST APPROUVÉ
function checkEstablishmentApproval($conn, $establishment_id) {
    $sql = "SELECT * FROM establishments WHERE id = :establishment_id AND status = 'approved'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':establishment_id', $establishment_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

