<?php
// TRAITEMENT DES DEMANDES PAR L'ADMIN
require_once '../models/database.php';
require_once '../models/request.php';
require_once '../models/send_mail.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action'], $_GET['id'])) {
    $conn = getConnexion();
    $action = $_GET['action'];
    $id = intval($_GET['id']); // Sécurisation de l'ID

    try {
        // Récupérer les informations de la demande
        $request = getRequestById($id);

        if (!$request) {
            die("Erreur : Demande introuvable.");
        }

        // Récupérer email et prénom
        $email = htmlspecialchars($request['mail_admin']);
        $firstname = htmlspecialchars($request['firstname_admin']);
        $lastname = htmlspecialchars($request['lastname_admin']);
        $phone = htmlspecialchars($request['phone']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Erreur : Email invalide.");
        }

        // Vérification du numéro de téléphone (format FR : 02XXXXXXXX, 06XXXXXXXX, 07XXXXXXXX ou 09XXXXXXXX)
        if (!preg_match("/^0(2|6|7|9)\d{8}$/", $phone)) {
            die("Erreur : Numéro de téléphone invalide.");
        }

        // Vérifier si l'email existe déjà dans la base de données
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE mail = ?");
        $stmt->execute([$email]);
        $emailExists = $stmt->fetchColumn();
  
        if ($emailExists > 0) {
            die("Erreur : Cet email est déjà utilisé.");
        }

        // Génération d'un identifiant unique (username)
        $username = strtolower(substr($firstname, 0, 1) . $lastname . rand(100, 999));

        // Générer un mot de passe temporaire
        $password_plain = bin2hex(random_bytes(4));
        $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

        // Vérifier que le mot de passe n'est pas vide
        if (empty($password_plain)) {
            die("Erreur : Le mot de passe ne peut pas être vide.");
        }

        if ($action === 'approve') {
            $conn->beginTransaction();

            // Insérer l'établissement en premier
            $stmt = $conn->prepare("INSERT INTO establishments (firstname, adresse, type_role, siret, phone, site, description, email) 
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
            
            // Récupérer l'ID de l'établissement nouvellement inséré
            $establishment_id = $conn->lastInsertId();  

            // Insérer l'utilisateur
            $stmt = $conn->prepare("INSERT INTO users (username, firstname, lastname, mail, password, establishment_id, role_id, must_change_password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        
            $stmt->execute([
                $username, 
                $firstname, 
                $lastname, 
                $email, 
                $password_hashed, 
                $establishment_id, 
                htmlspecialchars($request['role']),
            ]);
        
            // Mise à jour du statut de la demande
            updateRequestStatus($id, 'accepted');
        
            $conn->commit();
        
            // Envoyer un email d'acceptation
            $subject = "Demande d'inscription acceptée";
            $message = "<h2>Bonjour $firstname,</h2>";
            $message .= "<p>Votre demande d'inscription a été acceptée.</p>";
            $message .= "<p><strong>Identifiant :</strong> $username</p>";
            $message .= "<p><strong>Mot de passe temporaire :</strong> $password_plain</p>";
            $message .= "<p>Veuillez vous connecter et changer votre mot de passe dès que possible.</p>";
            $message .= "<p>Cordialement,</p>";
            $message .= "<p>L'équipe de Clara</p>";
        
            if (sendEmail($email, $subject, $message)) {
                echo "Demande approuvée et email envoyé.";
            } else {
                echo "Demande approuvée, mais l'email n'a pas pu être envoyé.";
            }
        } 
        elseif ($action === 'reject') { 
            updateRequestStatus($id, 'rejected');

            // Envoyer un email de refus
            $subject = "Votre demande d'inscription a été rejetée";
            $message = "<h2>Bonjour $firstname,</h2>";
            $message .= "<p>Nous sommes désolés, mais votre demande d'inscription a été refusée.</p>";
            $message .= "<p>Si vous avez des questions, contactez notre support.</p>";
            $message .= "<p>Cordialement,</p><p>L'équipe de Clara</p>";

            if (sendEmail($email, $subject, $message)) {
                echo "Demande rejetée et email envoyé.";
            } else {
                echo "Demande rejetée, mais l'email n'a pas pu être envoyé.";
            }
        } else {
            die("Action invalide.");
        }

    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        die("Erreur lors du traitement de la demande : " . $e->getMessage());
    }
} else {
    die("Accès non autorisé.");
}
?>



