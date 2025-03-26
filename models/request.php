<?php
// INSERTION DANS BASE DE DONNÉES
function createRequest($conn, $data) {
    try {
        // Sécuriser les données
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }

        $stmt = $conn->prepare("INSERT INTO requests (firstname_admin, lastname_admin, role, mail_admin, firstname_establishment, adresse, type_role, siret, mail, phone, site, description, cgu, status) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'en attente')");

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
            $data['cgu']
        ]);
    } catch (Exception $e) {
        error_log("Erreur lors de l'insertion de la demande : " . $e->getMessage());
        return false;
    }
}

// RÉCUPÉRER UNE DEMANDE PAR ID
function getRequestById($conn, $id) {
    try {
        $stmt = $conn->prepare("SELECT * FROM requests WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération de la demande : " . $e->getMessage());
        return false;
    }
}

// METTRE À JOUR LE STATUT D'UNE DEMANDE 
function updateRequestStatus($conn, $id, $status) {
    try {
        $stmt = $conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        return true;
    } catch (Exception $e) {
        error_log("Erreur lors de la mise à jour du statut : " . $e->getMessage());
        return false;
    }
}

// RÉCUPÉRER LES DEMANDES EN ATTENTE 
function getPendingRequests($conn, $search = '') {
    try {
        $query = "SELECT * FROM requests WHERE status = 'en attente'";
        if (!empty($search)) {
            $query .= " AND (firstname_establishment LIKE :search OR mail_admin LIKE :search OR created_at LIKE :search OR type_role LIKE :search)";
        }
        $query .= " ORDER BY created_at DESC";

        $stmt = $conn->prepare($query);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération des demandes en attente : " . $e->getMessage());
        return false;
    }
}

// RÉCUPÉRER LES DEMANDES ACCEPTÉES
function getApprovedRequests($conn, $search = '') {
    try {
        $query = "SELECT * FROM requests WHERE status = 'accepté'";
        if (!empty($search)) {
            $query .= " AND (firstname_establishment LIKE :search OR mail_admin LIKE :search OR created_at LIKE :search OR type_role LIKE :search)";
        }
        $query .= " ORDER BY created_at DESC";

        $stmt = $conn->prepare($query);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération des demandes acceptées : " . $e->getMessage());
        return false;
    }
}

// RÉCUPÉRER LES DEMANDES REJETÉES
function getRejectedRequests($conn, $search = '') {
    try {
        $query = "SELECT * FROM requests WHERE status = 'refusé'";
        if (!empty($search)) {
            $query .= " AND (firstname_establishment LIKE :search OR mail_admin LIKE :search OR created_at LIKE :search OR type_role LIKE :search)";
        }
        $query .= " ORDER BY created_at DESC";

        $stmt = $conn->prepare($query);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération des demandes refusées : " . $e->getMessage());
        return false;
    }
}

function updateRequest($conn, $id, $firstname_admin, $lastname_admin, $mail_admin, $firstname_establishment, $adresse, $type_role, $siret, $mail, $phone, $site, $description) {
    try {
        $sql = "UPDATE requests 
                SET firstname_admin = ?, 
                    lastname_admin = ?, 
                    mail_admin = ?, 
                    firstname_establishment = ?, 
                    adresse = ?, 
                    type_role = ?, 
                    siret = ?, 
                    mail = ?, 
                    phone = ?, 
                    site = ?, 
                    description = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $firstname_admin, $lastname_admin, $mail_admin, $firstname_establishment, 
            $adresse, $type_role, $siret, $mail, $phone, $site, $description, $id
        ]);
    } catch (PDOException $e) {
        error_log("Erreur lors de la mise à jour de la demande : " . $e->getMessage());
        return false;
    }
}

//SUPPRESSION DE LA REQUETE
function deleteRequest($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM requests WHERE id = ?");
    return $stmt->execute([$id]);
}
?>







