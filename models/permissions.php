<?php
// modele/permissions.php
require_once 'database.php';

// Fonction pour récupérer les permissions d'un utilisateur
function getUserPermissions($pdo, $userId) {
    $sql = "SELECT p.nom 
            FROM permissions p
            JOIN role_permissions rp ON p.id = rp.permission_id
            JOIN roles r ON rp.role_id = r.id
            JOIN users u ON u.role_id = r.id
            WHERE u.id = :userId";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Fonction pour vérifier si un utilisateur a une permission spécifique
function hasPermission($permissions, $permissionToCheck) {
    return in_array($permissionToCheck, $permissions);
}
?>
