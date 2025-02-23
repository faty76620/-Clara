<header class="header">
    <nav>
        <img src="/clara/assets/images/logo.png" class="logo" alt="logo">
        <ul id="nav-links">
            <li><a href="/clara/views/home/home.php" onclick="hideMenu()">ACCUEIL</a></li>
            <li id="select">
                <select onchange="window.location.href=this.value;">
                    <option value="#">FONCTIONNALITES</option>
                    <option value="/clara/views/home/planning.php">PLANNING</option>
                    <option value="/clara/views/home/patients.php">PATIENTS</option>
                    <option value="/clara/views/home/transmissions.php">TRANSMISSIONS</option>
                    <option value="/clara/views/home/soins-valides.php">SOINS VALIDES</option>
                </select>
            </li>
            <li><a href="/clara/views/home/clara.php" onclick="hideMenu()">CLARA</a></li>
            <li><a href="/clara/views/auth/login.php" onclick="hideMenu()">CONNEXION</a></li>
        </ul>
        <div id="burger" class="burger" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
    </nav>
</header>


