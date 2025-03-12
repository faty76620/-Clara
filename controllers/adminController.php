<?php
require_once '../models/database.php';
require_once '../models/request.php';
require_once '../models/establishment.php';
require_once '../models/user.php';
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

        //Récuperer les informations admin
        $email = htmlspecialchars($request['mail_admin']);
        $firstname = htmlspecialchars($request['firstname_admin']);
        $lastname = htmlspecialchars($request['lastname_admin']);
        $phone = htmlspecialchars($request['phone']);

        if ($action === 'approve') {
            $conn->beginTransaction(); // Début de la transaction
            // Créer l'établissement
            $establishment_id = createEstablishment($request);

            // Créer l'utilisateur
            $username = strtolower($request['firstname_admin'] . '.' . $request['lastname_admin']);
            $username = preg_replace('/[^a-z0-9.]/', '', $username); // Nettoyage
            $password_plain = bin2hex(random_bytes(4)); // Générer un mot de passe temporaire
            $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);

            createUser([
                'username' => $username,
                'firstname_admin' => $request['firstname_admin'],
                'lastname_admin' => $request['lastname_admin'],
                'mail_admin' => $request['mail_admin'],
                'password' => $password_hashed,
                'establishment_id' => $establishment_id,
                'role' => $request['role']
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

