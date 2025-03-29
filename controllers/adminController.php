<?php
require_once __DIR__ . '/../config.php'; 
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/request.php';
require_once MODEL_DIR . '/establishment.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/send_mail.php';
require_once MODEL_DIR . '/logs.php';
require_once TEMPLATE_DIR . '/session_start.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action'], $_GET['id'])) {
    $conn = getConnexion();
    $action = $_GET['action'];
    $id = intval($_GET['id']);

    try {
        // Récupérer la demande
        $request = getRequestById($conn, $id);
        if (!$request) {
            addLog('Erreur', $_SESSION['username'], "Demande introuvable - ID: $id");
            die("Erreur : Demande introuvable.");
        }

        // Vérifier si un manager existe déjà pour cet établissement
        if (checkManagerExists($conn, $request['establishment_id'])) {
            $_SESSION['error'] = "Un manager existe déjà pour cet établissement.";
            addLog('Échec', $_SESSION['username'], "Tentative de validation d'une demande avec un manager déjà existant - Établissement ID: " . $request['establishment_id']);
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

            $establishment_id = checkExistingEstablishment($conn, $request);

            if (!$establishment_id) {
                // Créer l'établissement s'il n'existe pas
                $establishment_id = createEstablishment($conn, $request);
                addLog('Création établissement', $_SESSION['username'], "Établissement créé - ID: $establishment_id");
            } 
            
            // Mettre à jour l'établissement
            updateEstablishmentStatus($conn, $establishment_id, 'accepté'); 
            
            if (!checkManagerExists($conn, $establishment_id)) {
                // Création du manager (admin)
                $username = strtolower($firstname . '.' . $lastname);
                $username = preg_replace('/[^a-z0-9.]/', '', $username); 

                $password_plain = bin2hex(random_bytes(4)); // Mot de passe temporaire
                $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);

                createAdmin($conn, [
                    'username' => $username,
                    'firstname_admin' => $firstname,
                    'lastname_admin' => $lastname,
                    'mail_admin' => $email,
                    'password' => $password_hashed,
                    'establishment_id' => $establishment_id,
                    'role' => $request['role']
                ]);

                addLog('Création manager', $_SESSION['username'], "Manager créé - $username ($email) pour établissement ID: $establishment_id");
            }

            // Mettre à jour le statut de la demande
            updateRequestStatus($conn, $id, 'accepté');
            $conn->commit();

            // Envoi de l'email
            $subject = "Demande d'inscription acceptée";
            $message = "<h2>Bonjour $lastname,</h2>";
            $message .= "<p>Votre demande a été acceptée.</p>";
            $message .= "<p>Identifiant : <strong>$username</strong></p>";
            $message .= "<p>Mot de passe temporaire : <strong>$password_plain</strong></p>";
            $message .= "<p>Veuillez le changer dès votre première connexion.</p>";
            $message .= "<p>Cordialement,</p><p>L'équipe de Clara</p>";

            if (sendEmail($email, $subject, $message)) {
                $_SESSION['success_message'] = "Demande approuvée et email envoyé.";
                addLog('Email envoyé', $_SESSION['username'], "Email de confirmation envoyé à $email");
            } else {
                $_SESSION['error_message'] = "Demande approuvée, mais l'email n'a pas pu être envoyé.";
                addLog('Erreur envoi email', $_SESSION['username'], "Échec d'envoi du mail à $email");
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

            addLog('Demande rejetée', $_SESSION['username'], "Rejet de la demande d'inscription de $firstname $lastname ($email)");

            header("Location: /clara/views/admin/dashboard.php");
            exit();
        }

    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        addLog('Erreur critique', $_SESSION['username'], "Erreur lors du traitement de la demande ID: $id - " . $e->getMessage());
        die("Erreur lors du traitement de la demande : " . $e->getMessage());
    }
} else {
    addLog('Accès non autorisé', $_SESSION['username'], "Tentative d'accès non autorisé à la validation de demande");
    die("Accès non autorisé.");
}
?>





