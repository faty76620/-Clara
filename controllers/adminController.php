<?php
require_once '../templates/session_start.php';
require_once '../models/database.php';
require_once '../models/request.php';
require_once '../models/establishment.php';
require_once '../models/user.php';
require_once '../models/send_mail.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action'], $_GET['id'])) {
    $conn = getConnexion(); 
    $action = $_GET['action'];
    $id = intval($_GET['id']); 

    try {
        // RÉCUPÉRATION DES INFORMATIONS DE LA DEMANDE
        $request = getRequestById($conn, $id);
        if (!$request) {
            die("Erreur : Demande introuvable.");
        }

        // VERIFIER SI UN MANAGER EXISTE DEJA POUR CET ETABLISSEMENT
        if (checkManagerExists($conn, $establishment_id)) {
        $_SESSION['error'] = "Un manager existe déjà pour cet établissement. Un seul manager est autorisé.";
        header("Location: /clara/views/manager/register_user.php");
        exit();
        }

        // EXTRAIRE ADMIN POUR INSERER DANS TABLE 'users'
        $email = htmlspecialchars($request['mail_admin']);
        $firstname = htmlspecialchars($request['firstname_admin']);
        $lastname = htmlspecialchars($request['lastname_admin']);
        $phone = htmlspecialchars($request['phone']);

        if ($action === 'approve') {
            $conn->beginTransaction(); 

            // CRÉER UN ÉTABLISSEMENT
            $establishment_id = createEstablishment($conn, $request);

            // CRÉER UN UTILISATEUR
            $username = strtolower($request['firstname_admin'] . '.' . $request['lastname_admin']);
            $username = preg_replace('/[^a-z0-9.]/', '', $username); // Nettoyage
            $password_plain = bin2hex(random_bytes(4)); // Générer un mot de passe temporaire
            $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);
           
            try {
                createAdmin($conn, [
                    'username' => $username,
                    'firstname_admin' => $request['firstname_admin'],
                    'lastname_admin' => $request['lastname_admin'],
                    'mail_admin' => $request['mail_admin'],
                    'password' => $password_hashed,
                    'establishment_id' => $establishment_id,
                    'role' => $request['role']
                ]);

                // METTRE À JOUR LE STATUT "ACCEPTÉ"
                updateRequestStatus($conn, $id, 'accepté');

                $conn->commit();

                // ENVOYER UN EMAIL DE CONFIRMATION
                $subject = "Demande d'inscription acceptée";
                $message = "<h2>Bonjour $lastname,</h2>";
                $message .= "<p>Après vérification de vos informations,</p>";
                $message .= "<p>Votre demande d'inscription a été acceptée.</p>";
                $message .= "<p>Voici votre identifiant de connexion : <strong>$username</strong></p>";
                $message .= "<p>Votre mot de passe temporaire est : <strong>$password_plain</strong></p>";
                $message .= "<p>Pour des raisons de sécurité, nous vous recommandons de changer ce mot de passe dès votre première connexion.</p>";
                $message .= "<p>Cordialement,</p><p>L'équipe de Clara</p>";

                if (sendEmail($email, $subject, $message)) {
                    $_SESSION['success_message'] = "Demande approuvée et email envoyé.";
                    } else {
                    $_SESSION['error_message'] = "Demande approuvée, mais l'email n'a pas pu être envoyé.";
                }

                header("Location: /clara/views/admin/dashboard.php"); 
                exit();  

            } catch (Exception $e) {
                // ANNULER TRANSACTION EN CAS D'ERREUR
                $conn->rollBack();
                echo "Erreur lors de la soumission de la demande : " . $e->getMessage();
                header("Location: /clara/views/home/request_erreur.php");
                exit();
            }

        } elseif ($action === 'reject') { 
            updateRequestStatus($conn, $id, 'rejeté');

            // EMAIL DE REFUS
            $subject = "Votre demande d'inscription a été rejetée";
            $message = "<h2>Bonjour $firstname,</h2>";
            $message .= "<p>Nous sommes désolés, mais votre demande d'inscription a été refusée.</p>";
            $message .= "<p>Si vous avez des questions, contactez notre support.</p>";
            $message .= "<p>Cordialement,</p><p>L'équipe de Clara</p>";

            if (sendEmail($email, $subject, $message)) {
                $_SESSION['success_message'] = "Demande rejetée et email envoyé.";
            } else {
                $_SESSION['error_message'] = "Demande rejetée, mais l'email n'a pas pu être envoyé.";
            }
            
            header("Location: /clara/views/admin/dashboard.php"); 
            exit();
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


