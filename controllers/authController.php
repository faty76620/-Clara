<?php
require_once __DIR__ . '/../templates/session_start.php';
require_once __DIR__ . '/../models/database.php';
require_once __DIR__ . '/../models/user.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Connexion à la base de données
    $conn = getConnexion();
    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

    // Récupérer les données du formulaire
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Vérification des champs vides
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        header("Location: /clara/views/auth/login.php");
        exit();
    }

    // Récupérer l'utilisateur depuis la base de données
    $user = getUserByUsername($conn, $username);


    $conn = getConnexion();

    error_log("Mot de passe soumis : " . $password);
    error_log("Mot de passe en base : " . $user['password']);

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Identifiant ou mot de passe incorrect.";
        header("Location: /clara/views/auth/login.php");
        exit();
    }

    // Stocker les informations de l'utilisateur en session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role_id'] = $user['role_id'];
    $_SESSION['firstname'] = $user['firstname']; 
    $_SESSION['lastname'] = $user['lastname'];    
    $_SESSION['must_change_password'] = $user['must_change_password'];
    
    // Si l'utilisateur doit changer son mot de passe, le rediriger vers la page de changement de mot de passe
    if ($user['must_change_password'] == 1) {
        header("Location: /clara/views/auth/change_password.php");
        exit();
    }

    // Sinon, rediriger vers le tableau de bord en fonction du rôle
    switch ($user['role_id']) {
        case 1:
            // Si l'utilisateur est un administrateur (role_id = 1)
            header("Location: /clara/views/admin/dashboard.php");
            break;

        case 2:
            // Si l'utilisateur est un manager (role_id = 2)
            header("Location: /clara/views/manager/dashboard.php");
            break;

        case 3:
            // Si l'utilisateur est un utilisateur classique (role_id = 3)
            header("Location: /clara/views/user/dashboard.php");
            break;

        default:
            // Si le rôle de l'utilisateur n'est pas défini ou est incorrect
            $_SESSION['error'] = "Rôle d'utilisateur inconnu.";
            header("Location: /clara/views/auth/login.php");
            break;
    }

    exit(); 
}
?>








