<?php
require_once '/clara/models/database.php';

// FONCTION POUR CRÉER UN NOUVEL ÉTABLISSEMENT DANS LA BASE DE DONNÉES
function createEstablishment($data) {
    $conn = getConnexion();
    $stmt = $conn->prepare("INSERT INTO establishments (firstname, address, type, siret, phone, site, description, created_at, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['firstname'],
        $data['address'],
        $data['type'],
        $data['siret'],
        $data['phone'],
        $data['site'],
        $data['description'],
        $data['created_at'],
        $data['email']
    ]);
}

// FONCTION POUR RÉCUPÉRER LES INFORMATIONS D'UN ÉTABLISSEMENT À PARTIR DE LA BASE DE DONNÉES EN UTILISANT SON ID
function getEstablishmentById($id) {
    $conn = getConnexion();
    $stmt = $conn->prepare("SELECT * FROM establishments WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// FONCTION POUR METTRE À JOUR LES INFORMATIONS D'UN ÉTABLISSEMENT EXISTANT DANS LA BASE DE DONNÉES
function updateEstablishment($id, $data) {
    $conn = getConnexion();
    $stmt = $conn->prepare("UPDATE establishments SET firstname = ?, address = ?, type = ?, siret = ?, phone = ?, site = ?, description = ?, created_at = ?,  email = ? WHERE id = ?");
    $stmt->execute([
        $data['firstname'],
        $data['address'],
        $data['type'],
        $data['siret'],
        $data['phone'],
        $data['site'],
        $data['description'],
        $data['created_at'],
        $data['email']
    ]);
}

// FONCTION POUR SUPPRIMER UN ÉTABLISSEMENT DE LA BASE DE DONNÉES EN UTILISANT SON ID
function deleteEstablishment($id) {
    $conn = getConnexion();
    $stmt = $conn->prepare("DELETE FROM establishments WHERE id = ?");
    $stmt->execute([$id]);
}

// FONCTION POUR RÉCUPÉRER TOUS LES ÉTABLISSEMENTS DE LA BASE DE DONNÉES
function getAllEstablishments() {
    $conn = getConnexion();
    $stmt = $conn->prepare("SELECT * FROM establishments");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>