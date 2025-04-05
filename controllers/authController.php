<?php

require_once __DIR__ . '/../config.php'; 
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/logs.php';
require_once TEMPLATE_DIR . '/session_start.php'; 


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

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if (!$user || !password_verify($password, $user['password'])) {
        addLog('Échec de connexion', null, "Tentative de connexion échouée pour '$username'");
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
    $_SESSION['establishment_id'] = $user['establishment_id'];

    // Log de connexion réussie
    addLog('Connexion réussie', $user['id'], "Utilisateur '{$user['username']}' connecté avec succès");

    // Si l'utilisateur doit changer son mot de passe, le rediriger vers la page de changement de mot de passe
    if ($user['must_change_password'] == 1) {
        addLog('Changement de mot de passe requis', $user['id'], "Utilisateur '{$user['username']}' doit changer son mot de passe.");
        header("Location: /clara/views/auth/change_password.php");
        exit();
    }

    // Redirection selon le rôle de l'utilisateur
    switch ($user['role_id']) {
        case 1:
            header("Location: /clara/views/admin/dashboard.php");
            break;
        case 2:
            header("Location: /clara/views/manager/dashboard.php");
            break;
        case 3:
            header("Location: /clara/views/user/dashboard.php");
            break;
        default:
            $_SESSION['error'] = "Rôle utilisateur inconnu.";
            addLog('Échec de connexion', $user['id'], "Rôle utilisateur inconnu pour '{$user['username']}'");
            header("Location: /clara/views/auth/login.php");
            break;
    }

    exit();
}
?>












