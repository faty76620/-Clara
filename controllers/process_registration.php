<?php

// TRAITEMENT DES DONNEES A L'ENVOI
require_once __DIR__ . '/../config.php';        
require_once MODEL_DIR . '/database.php';        
require_once MODEL_DIR . '/request.php';  
require_once MODEL_DIR . '/logs.php';          


// VERIFIE SI FORMULAIRE EST SOUMIT
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // VERIFIER SI CHAMPS SONT BIEN REMPLIE
    if (
        isset(
            $_POST['firstname_admin'], $_POST['lastname_admin'], $_POST['role'], $_POST['mail_admin'],
            $_POST['firstname_establishment'], $_POST['adresse'], $_POST['type_role'],
            $_POST['siret'], $_POST['mail'], $_POST['phone'], $_POST['site'], $_POST['description'], $_POST['cgu']
        ) &&
        !empty($_POST['firstname_admin']) && !empty($_POST['lastname_admin']) && !empty($_POST['role']) &&
        !empty($_POST['mail_admin']) && !empty($_POST['firstname_establishment']) && !empty($_POST['adresse']) &&
        !empty($_POST['type_role']) && !empty($_POST['siret']) && !empty($_POST['mail']) &&
        !empty($_POST['phone']) && !empty($_POST['site']) && !empty($_POST['description'])
    ) {
        
        // CONNEXION A LA BASE DE DONNES
        $conn = getConnexion();

        // RECUPERER LES DONNEES DU FORMULAIRE ET SECURISER
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
        
        // VALIDATION DES SPECIFIQUE
        if (!filter_var($mail_admin, FILTER_VALIDATE_EMAIL) || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            die("Adresse email invalide.");
        }
        if (!preg_match('/^[0-9]{10}$/', $phone)) {
            die("Numéro de téléphone invalide.");
        }
        if (!preg_match('/^[0-9]{14}$/', $siret)) {
            die("Numéro de SIRET invalide.");
        }
        if (!filter_var($site, FILTER_VALIDATE_URL)) {
            die("URL du site invalide.");
        }
        
        try {
            $conn->beginTransaction();
            // INSERE LA DEMANDE DANS LA TABLE 'requests'
            createRequest($conn, [
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
            
            // VALIDE TRANSACTION 
            $conn->commit();

            echo "Votre demande a été soumise avec succès. Vous recevrez un email après validation par un administrateur.";
            header("Location: ../views/home/request_success.php");
            exit();

        } catch (Exception $e) {
            // ANNULER TRANSACTION EN CAS D'ERREUR
            $conn->rollBack();
            echo "Erreur lors de la soumission de la demande : " . $e->getMessage();
            header("Location: /clara/views/home/request_erreur.php");
            exit();
        }
    } else {
        die("Tous les champs sont obligatoires.");
    }
} else {
    echo "Requête invalide.";
}
?>









