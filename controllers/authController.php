<?php
session_start(); 
require_once '../models/database.php';
require_once '../models/user.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $conn = getConnexion();
        if (!$conn) {
            die("Erreur de connexion à la base de données.");
        }

        $username = htmlspecialchars(trim($_POST['username']));
        $password = htmlspecialchars(trim($_POST['password']));
        $user = getUserByUsername($conn, $username);
        
        if (!$user) {
            $_SESSION['error'] = "Identifiant incorrect.";
            header("Location: /clara/views/auth/login.php");
            exit();
        }
        
        if (!$user || !isset($user['password']) || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = "Mot de passe incorrect.";
            header("Location: /clara/views/auth/login.php");
            exit();
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role_id'];
        $_SESSION['must_change_password'] = $user['must_change_password'];
        $_SESSION['lastname'] = $user['lastname'];
        
        //REDIRECTION SUR LA PAGE DE CHANGEMENT DE MOT DE PASSE SELON LE ROLE
        if ($user['must_change_password'] == 1) {
            header("Location: /clara/views/auth/change_password.php");
        } else {
            switch ($user['role_id']) {
                case 1:
                    header("Location: /clara/views/admin/dashboard.php");
                    break;
                case 2:
                    header("Location: /clara/views/manager/dashboard.php");
                    break;
                default:
                    header("Location: /clara/views/user/dashboard.php");
                    break;
            }
        }
        exit();
    }
}
?>

