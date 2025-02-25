<?php
require_once '../models/database.php';
require_once '../models/send_mail.php';

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $conn = getConnexion();
    $action = $_GET['action'];
    $id = $_GET['id'];

    // Récupérer les informations de la demande
    $stmt = $conn->prepare("SELECT * FROM requests WHERE id = ?");
    $stmt->execute([$id]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        echo "Demande non trouvée.";
        exit();
    }

    if ($request) {
        $email = $request['mail_admin'];
        $firstname = htmlspecialchars($request['firstname_admin']);

        if ($action === 'approve') {
            // Approuver la demande
            $stmt = $conn->prepare("UPDATE requests SET status = 'accepted' WHERE id = ?");
            $stmt->execute([$id]);

            // Générer un mot de passe temporaire
            $password = bin2hex(random_bytes(4)); // Génère un mot de passe de 8 caractères
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insérer l'admin dans la table `users`
            $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'admin')");
            $stmt->execute([$email, $hashedPassword]);

            // Envoyer un email d'acceptation
            $subject = "Demande d'inscription acceptée";
            $message = "<h2>Bonjour $firstname,</h2>";
            $message .= "<p>Votre demande d'inscription a été acceptée.</p>";
            $message .= "<p>Identifiants</p>";
            $message .= "</p><strong>Email:</strong>$email</p>";
            $message .= "</p><strong>Mot de passe temporaire:</strong>$password</p>";
            $message .= "<p>Veuillez vous connecter et changer votre mot de passe.</p>";
            $message .= "<p>Cordialement,</p>";
            $message .= "<p>L'équipe de Clara</p>";

            if (sendEmail($email, $subject, $message)) {
                echo "Demande approuvée et email envoyé.";
            } else {
                echo "Demande approuvée, mais l'envoi de l'email a échoué.";
            }

        } elseif ($action === 'reject') {
            // mise a jour du status
            $stmt = $conn->prepare("UPDATE requests SET status = 'rejected' WHERE id = ?");
            $stmt->execute([$id]);

            // Envoyer un email de refus
            $subject = "Demande d'inscription rejetée";
            $message = "<h1>Bonjour $firstname,</h1>";
            $message .= "<p>Votre demande d'inscription a été rejetée.</p>";
            $message .= "<p>Cordialement,</p>";
            $message .= "<p>L'équipe de Clara</p>";

            if (sendEmail($email, $subject, $message)) {
                echo "Demande rejetée et email envoyé.";

            } else {
                echo "Demande rejetée, mais l'envoi de l'email a échoué.";
            }
        }
    } 
       
    exit();
}

?>