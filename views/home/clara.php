<!DOCTYPE html>
<html lang="en">
    <head>
    <?php 
    $title = "Clara"; 
    include __DIR__ . '/../../templates/head.php'; ?>
    <body>
        <?php 
        include __DIR__ . '/../../templates/header.php'; ?>
        <main class="main">
            <div class="card-clara">
                <h2>Qui somme nous ?</h2>
                <div class="intro">
                    <p>
                        Une entreprise spécialisée dans la création de sites web sur mesure pour les établissements de santé. 
                        Nous concevons des solutions numériques innovantes pour simplifier le travail des soignants et optimiser la gestion des services de soins a domicile. Nous accompagnons tous les acteurs du secteur de la santé en leur proposant des plateformes ergonomiques et sécurisées, adaptées à leurs besoins spécifiques.
                        <br></br>CLARA, est un hommage à <a id="clara-barton" href="https://fr.wikipedia.org/wiki/Clara_Barton"  target="_blank">Clara Barton</a>,
                        une pionnière du domaine de la santé et fondatrice de la Croix-Rouge américaine,
                        elle a consacré sa vie à l'aide aux personnes en détresse, posant les bases des secours humanitaires modernes. 
                        À son image, La technologie peut jouer un rôle essentiel pour soutenir et faciliter le travail des soignants.
                    </p>
                </div>
                <div class="line">
                    <div class="card">
                        <div class="card-content">
                            <h3>Notre mission</h3>
                            <ul>
                                <li>Soutenir les soignant.</li>
                                <li>Améliorer les soins à domicile.</li>
                                <li>Renforcer la colaboration.</li>
                                <li>simplifier l'organisation.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <h3>Obectifs</h3>
                            <ul>
                                <li>Faciliter le travail des soignants en réduisant les tâches administratives.</li>
                                <li>Optimiser la gestion des établissements avec des outils numériques intuitifs.</li>
                                <li>Améliorer la coordination des soins grâce à des solutions connectées et sécurisées.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="line">
                    <div class="card">
                        <div class="card-content">
                            <h3>Nos valeurs</h3>
                            <ul>
                                <li> Innovation – Apporter des solutions modernes et adaptées aux besoins des soignants.</li>
                                <li> Engagement – Faciliter le quotidien des professionnels de santé avec des outils performants.</li>
                                <li> Fiabilité – Garantir des services sécurisés et conformes aux normes médicales.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <h3>Nos services</h3>
                            <ul>
                                <li>Création de sites web sur mesure</li>
                                <li>Plannings intéractives</li>
                                <li>Dossiers patients dématérialisés</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main> 
        <footer>
        <?php include __DIR__ . '/../../templates/footer.php';?>
        </footer>   

    </body>
</html>