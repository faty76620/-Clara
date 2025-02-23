<!DOCTYPE html>
<html lang="en">
<?php 
    $title = "Demande inscription"; 
    include __DIR__ . '/../../templates/head.php'; ?>
    <body>
        <?php 
        include __DIR__ . '/../../templates/header.php'; ?>
        <main class="main">
            <h2>Demande d'incription</h2>
            <div  class="flex-register">
                <div class="register-container">
                    <form action="/clara/controllers/registrationController.php" method="post">
                        <div class="register-admin">
                            <h3>Informations du responsable (Admin)</h3>
                            <div class="form-group">
                                <label for="firstname_admin">Nom du responsable :</label>
                                <div class="input-wrapper">
                                    <input type="text" id="firstname_admin" name="firstname_admin" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name_admin">Prénom du responsable :</label>
                                <div class="input-wrapper">
                                    <input type="text" id="name_admin" name="name_admin" required>
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