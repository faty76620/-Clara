<head>
    <?php 
        $title = "Accueil"; 
        include '../templates/head.php'; ?>
    </head>
<body class="body-header">
    <div class="part-connect">
        <div class="logo-connect">
            <img id="logo-connexion" src="/clara/assets/images/logo.png">
        </div>
        <div class="container-form-login">
            <form action="/clara/controller/auth/login.php" method="post" class="form-login">
                <div class="group-form">
                    <i class="fa-solid fa-user" style="color:#49667f;"></i>
                    <input type="username" id="username" placeholder="identifiant" required> 
                </div>
                <div class="group-form">
                    <i class="fa-solid fa-eye" style="color:#49667f ;"></i>
                    <input type="password" id="password" placeholder="Mot de passe" required>
                </div>
                <div class="group-form">
                    <button  class="btn" type="button">Connexion</button>
                </div>
                <div class="password-forgot">
                    <a href="#" class="open-modal">Mot de passe oublié?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>