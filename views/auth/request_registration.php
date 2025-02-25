<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/clara/assets/images/logo-onglet.png">
    <link rel="preload" as="image" href="/clara/assets/images/img-banner.jpg">
    <link rel="stylesheet" href="/clara/assets/css/style.css">
    <link rel="stylesheet" href="/clara/assets/css/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/15ca748f1e.js" crossorigin="anonymous"></script>
    <script defer src="/clara/assets/js.js"></script>
    <title>Demande inscription</title>
</head>
    <body>
        <?php 
        include __DIR__ . '/../../templates/header.php'; ?>
        <main class="main">
            <h2>Demande d'incription</h2>
            <div  class="flex-register">
                <div class="register-container">
                    <form action="/clara/controllers/registrationController.php" method="POST">
                        <div class="register-admin">
                            <h3>Informations du responsable (Admin)</h3>
                            <div class="form-group">
                                <label for="firstname_admin">Nom du responsable :</label>
                                <div class="input-wrapper">
                                    <input type="text" id="firstname_admin" name="firstname_admin" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lastname_admin">Prénom du responsable :</label>
                                <div class="input-wrapper">
                                    <input type="text" id="lastname_admin" name="lastname_admin" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="role">Rôle :</label>
                                <div class="input-wrapper">
                                    <select id="role" name="role" required>
                                        <option value="1">Admin</option>
                                        <option value="2">Utilisateur</option>
                                    </select>
                                </div>
                            </br>
                            </div>
                            <div class="form-group">
                                <label for="mail_admin">Email du responsable :</label>
                                <div class="input-wrapper">
                                    <input type="email" id="mail_admin" name="mail_admin" required>
                                </div>
                            </div>
                            <br>
                            <div class="img-register">
                                <img src="/clara/assets/images/soignante.gif" alt="soignante prnenant une fleur">
                            </div>
                        </div>
                        <br>
                        <div class="separator-line"></div>
                        <br>
                        <div>
                            <h3>Informations Etablissement</h3>
                            <div class="form-group">
                                <label for="firstname-establishment">Nom de l'établissement ou service :</label>
                                <div class="input-wrapper">
                                <input type="text" id="firstname_establishment" name="firstname_establishment" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="adresse">Adresse :</label>
                                <div class="input-wrapper">
                                    <input type="text" id="adresse" name="adresse" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Type d'établissement *</label>
                                <select name="type_role" required>
                                    <option value="cabinet">Cabinet</option>
                                    <option value="service_domicile">Service à domicile</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="siret">Siret :</label>
                                <div class="input-wrapper">
                                <input type="text" id="siret" name="siret" maxlength="14" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mail">Email :</label>
                                <div class="input-wrapper">
                                    <input type="email" id="mail" name="mail" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Téléphone :</label>
                                <div class="input-wrapper">
                                <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="site">Site web :</label>
                                <div class="input-wrapper">
                                    <input type="url" id="site" name="site" required>
                                </div>
                            </div>
                            <div class="form group">
                                <label for="description">Description de l'établissement ou service :</label>
                                <div class="input-wrapper">
                                    <textarea id="description" name="description" style="width: 100%;" required></textarea>
                                </div>
                            </div>
                            <div class="accept-conditions">
                                <input type="checkbox" name="cgu" class="cgu" required>j'accepte les <a href="#mention" style="color: black;">conditions générales d'utilisation</a>
                            </div>
                            <br>
                            <div class="flex-btn-submit">
                                <button type="submit">Soumettre</button>
                            </div>
                        </div>
                    </form>
                </div> 
            </div>
        </main>
        <footer>
            <?php include __DIR__ . '/../../templates/footer.php';?>
        </footer>
    </body>
</html>