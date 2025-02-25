<?php
require_once 'models/database.php'; // Assurez-vous d'avoir le fichier pour la connexion à la base de données

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données du formulaire
    $firstname_admin = $_POST['firstname_admin'];
    $lastname_admin = $_POST['lastname_admin'];
    $role = $_POST['role'];
    $mail_admin = $_POST['mail_admin'];
    $firstname_establishment = $_POST['firstname_establishment'];
    $adresse = $_POST['adresse'];
    $type_role = $_POST['type_role'];
    $siret = $_POST['siret'];
    $mail = $_POST['mail'];
    $phone = $_POST['phone'];
    $site = $_POST['site'];
    $description = $_POST['description'];

    // Connexion à la base de données
    $conn = getConnexion();

    try {
        // Démarrer une transaction
        $conn->beginTransaction();

        // Insérer l'établissement
        $stmt = $conn->prepare("INSERT INTO establishments (firstname, adresse, type_role, siret, phone, site, description, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$firstname_establishment, $adresse, $type_role, $siret, $phone, $site, $description, $mail]);
        $establishment_id = $conn->lastInsertId();

        // Insérer l'utilisateur
        $stmt = $conn->prepare("INSERT INTO users (establishment_id, firstname, lastname, mail, role_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$establishment_id, $firstname_admin, $lastname_admin, $mail_admin, $role]);

        // Valider la transaction
        $conn->commit();

        // Message de succès
        echo "Inscription réussie !";

    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $conn->rollBack();
        echo "Erreur lors de l'inscription : " . $e->getMessage();
    }

} else {
    echo "Aucune donnée soumise.";
}

?>










