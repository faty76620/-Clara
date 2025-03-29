<?php

// FONCTION POUR INSERER LES LOGS DANS BASE DE DONNEE
function addLog($action, $user_id = null, $description = null) {
    try {
        $conn = getConnexion(); 
        $stmt = $conn->prepare("INSERT INTO logs (user_id, action, description, timestamp) 
                                VALUES (:user_id, :action, :description, NOW())");
        $stmt->execute([
            ':user_id' => $user_id,   // Si l'utilisateur n'est pas connecté, user_id peut être null
            ':action' => $action,
            ':description' => $description
        ]);

    } catch (PDOException $e) {
        error_log("Erreur lors de l'ajout du log : " . $e->getMessage());
    }
}
// FONCTION POUR RECUPERER LES LOGS Y COMPRIS IDENTIFIANT DANS USERS
function getLogsWithUserInfo($conn) {
    try {
        $stmt = $conn->prepare("
            SELECT logs.id, logs.action, logs.description, logs.timestamp, users.username
            FROM logs
            INNER JOIN users ON logs.user_id = users.id
            ORDER BY logs.timestamp DESC
        ");
        
        // Exécuter la requête
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération des logs : " . $e->getMessage());
        return false; // Retourner false si une erreur survient
    }
}

?>