
<?php
// AJOUTER UNE TRANSMISSION
// INSERTION DANS LA TABLE TRANSMISSIONS
function createTransmission($conn, $data) {
    try {
        // Sécuriser les données
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }

        // Préparer la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO transmissions (patient_id, transmitted_by, transmission_date, transmission_description) 
                                VALUES (?, ?, ?, ?)");

        // Exécuter la requête avec les données sécurisées
        return $stmt->execute([
            $data['patient_id'],
            $data['transmitted_by'],
            $data['transmission_date'],
            $data['transmission_description'],
        ]);
    } catch (Exception $e) {
        error_log("Erreur lors de l'insertion de la transmission : " . $e->getMessage());
        return false;
    }
}

// FONCTION POUR RECUPERER TOUTES LES TRANSMISSION
function getTransmissionsByPatient($conn, $patient_id) {
    $sql = "
        SELECT t.transmission_id, t.transmitted_by, t.transmission_date, t.transmission_description
        FROM transmissions t
        JOIN patients p ON t.patient_id = p.patient_id
        WHERE p.patient_id = ?
        ORDER BY t.transmission_date DESC";  
    $stmt = $conn->prepare($sql);
    $stmt->execute([$patient_id]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Fonction pour récupérer les transmissions avec les informations de l'utilisateur ayant effectué la transmission
function getTransmissionsWithUserInfo($conn) {
    try {
        // Requête SQL pour récupérer les informations des transmissions avec le nom et prénom de l'utilisateur
        $sql = "SELECT t.transmission_id, t.patient_id, u.firstname, u.lastname, t.transmission_date, t.transmission_description
                FROM transmissions t
                JOIN users u ON t.transmitted_by = u.id";

        // Exécution de la requête
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Retourner les résultats sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // En cas d'erreur, retourner un message d'erreur ou gérer l'exception
        error_log("Erreur lors de la récupération des transmissions : " . $e->getMessage());
        return false;
    }
}

?>