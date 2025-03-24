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
        // Récupérer la demande
        $request = getRequestById($conn, $id);
        if (!$request) {
            die("Erreur : Demande introuvable.");
        }

        // Vérifier si un manager existe déjà pour cet établissement
        if (checkManagerExists($conn, $request['establishment_id'])) {
            $_SESSION['error'] = "Un manager existe déjà pour cet établissement.";
            header("Location: /clara/views/manager/register_user.php");
            exit();
        }

        // Récupérer les données de l'utilisateur (manager)
        $email = htmlspecialchars($request['mail_admin']);
        $firstname = htmlspecialchars($request['firstname_admin']);
        $lastname = htmlspecialchars($request['lastname_admin']);
        $phone = htmlspecialchars($request['phone']);

        if ($action === 'approve') {
            $conn->beginTransaction();

            // Vérifier si l'établissement existe déjà (cas où il a été refusé avant)
            $establishment_id = checkExistingEstablishment($conn, $request);
            
            if (!$establishment_id) {
                // Créer l'établissement s'il n'existe pas
                $establishment_id = createEstablishment($conn, $request);
            } else {
                // Mettre à jour l'établissement existant
                updateEstablishmentStatus($conn, $establishment_id, 'en attente');
            }

            // Vérifier si le manager existe déjà pour cet établissement
            if (!checkManagerExists($conn, $establishment_id)) {
                // Créer l'utilisateur manager
                $username = strtolower($firstname . '.' . $lastname);
                $username = preg_replace('/[^a-z0-9.]/', '', $username); // Nettoyage

                $password_plain = bin2hex(random_bytes(4)); // Mot de passe temporaire
                $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);

                // Creer manager(admin)
                createAdmin($conn, [
                    'username' => $username,
                    'firstname_admin' => $firstname,
                    'lastname_admin' => $lastname,
                    'mail_admin' => $email,
                    'password' => $password_hashed,
                    'establishment_id' => $establishment_id,
                    'role' => $request['role']
                ]);
            }

            // Mettre à jour le statut de la demande
            updateRequestStatus($conn, $id, 'accepté');
            $conn->commit();

            // Envoyer un email de confirmation
            $subject = "Demande d'inscription acceptée";
            $message = "<h2>Bonjour $lastname,</h2>";
            $message .= "<p>Votre demande a été acceptée.</p>";
            $message .= "<p>Identifiant : <strong>$username</strong></p>";
            $message .= "<p>Mot de passe temporaire : <strong>$password_plain</strong></p>";
            $message .= "<p>Veuillez le changer dès votre première connexion.</p>";
            $message .= "<p>Cordialement,</p><p>L'équipe de Clara</p>";

            if (sendEmail($email, $subject, $message)) {
                $_SESSION['success_message'] = "Demande approuvée et email envoyé.";
            } else {
                $_SESSION['error_message'] = "Demande approuvée, mais l'email n'a pas pu être envoyé.";
            }

            header("Location: /clara/views/admin/dashboard.php");
            exit();
        }

        elseif ($action === 'reject') {
            updateRequestStatus($conn, $id, 'rejeté');

            // Email de refus
            $subject = "Votre demande d'inscription a été rejetée";
            $message = "<h2>Bonjour $firstname,</h2>";
            $message .= "<p>Nous sommes désolés, mais votre demande a été refusée.</p>";
            $message .= "<p>Si vous avez des questions, contactez notre support.</p>";
            $message .= "<p>Cordialement,</p><p>L'équipe de Clara</p>";

            sendEmail($email, $subject, $message);
            $_SESSION['success_message'] = "Demande rejetée et email envoyé.";

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



