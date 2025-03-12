<?php
// Inclusion de l'autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Envoyer un email d'acceptation 
        function sendEmail($to, $subject, $message) {
            $mail = new PHPMailer(true);
            try {
                // Configuration du serveur SMTP 
                $mail->isSMTP();
                $mail->Host       = 'smtp.mailtrap.io'; 
                $mail->SMTPAuth   = true;
                $mail->Username   = '1cd229416c2168';        
                $mail->Password   = '1f0d41124ea80c';        
                $mail->SMTPSecure = 'tls';                 
                $mail->Port       = 2525;                  
        
                // Définir l'expéditeur et le destinataire
                $mail->setFrom('admin@clara.com', 'Clara Admin');
                $mail->addAddress($to);
        
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
                
                // Contenu de l'email
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $message;
                $mail->AltBody = strip_tags($message); // Version texte sans HTML
        
                $mail->send();     
                return true;
            } catch (Exception $e) {
                 
                error_log("Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo);
                return false; 
            }
        }
?>