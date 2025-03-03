<?php
// TRAITEMENT DES DONNEES A L'ENVOI
require_once '../models/database.php'; 
require_once '../models/request.php'; 

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Connexion à la base de données
    $conn = getConnexion();

    // Récupérer les données du formulaire et les sécuriser
    $firstname_admin = htmlspecialchars(trim($_POST['firstname_admin']));
    $lastname_admin = htmlspecialchars(trim($_POST['lastname_admin']));
    $role = htmlspecialchars(trim($_POST['role'])); 
    $mail_admin = filter_var($_POST['mail_admin'], FILTER_SANITIZE_EMAIL);
    $firstname_establishment = htmlspecialchars(trim($_POST['firstname_establishment']));
    $adresse = htmlspecialchars(trim($_POST['adresse']));
    $type_role = htmlspecialchars(trim($_POST['type_role']));
    $siret = htmlspecialchars(trim($_POST['siret']));
    $mail = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $site = filter_var($_POST['site'], FILTER_SANITIZE_URL);
    $description = htmlspecialchars(trim($_POST['description']));
    $cgu = isset($_POST['cgu']) ? 1 : 0;

    $password_plain = bin2hex(random_bytes(4));
       
    try {
        // Démarrer une transaction
        $conn->beginTransaction();
        // Insérer la demande dans la table `requests`
        createRequest([
            'firstname_admin' => $firstname_admin,
            'lastname_admin' => $lastname_admin,
            'role' => $role, 
            'mail_admin' => $mail_admin,
            'firstname_establishment' => $firstname_establishment,
            'adresse' => $adresse,
            'type_role' => $type_role,
            'siret' => $siret,
            'mail' => $mail,
            'phone' => $phone,
            'site' => $site,
            'description' => $description,
            'cgu' => $cgu,
        ]);

        // Valider la transaction
        $conn->commit();

        echo "Votre demande a été soumise avec succès. Vous recevrez un email après validation par un administrateur.";
        header("Location: ../views/home/request_success.php");
        exit();

    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $conn->rollBack();
        echo "Erreur lors de la soumission de la demande : " . $e->getMessage();
        header("Location: ../views/home/request_erreur.php");
        exit();
    }
} else {
    echo "Requête invalide.";
}

?>








