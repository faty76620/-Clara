<?php
require_once '/clara/models/database.php';

// FONCTION POUR CRÉER UN NOUVEL ÉTABLISSEMENT DANS LA BASE DE DONNÉES
function createEstablishment($data) {
    $conn = getConnexion();
    $stmt = $conn->prepare("INSERT INTO establishments (name, address, type, siret, email, phone, website, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['name'],
        $data['address'],
        $data['type'],
        $data['siret'],
        $data['email'],
        $data['phone'],
        $data['website'],
        $data['description']
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
    $stmt = $conn->prepare("UPDATE establishments SET name = ?, address = ?, type = ?, siret = ?, email = ?, phone = ?, website = ?, description = ? WHERE id = ?");
    $stmt->execute([
        $data['name'],
        $data['address'],
        $data['type'],
        $data['siret'],
        $data['email'],
        $data['phone'],
        $data['website'],
        $data['description'],
        $id
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