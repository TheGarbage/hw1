<?php session_start(); ?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Ludoteca_contatti</title>
        <link rel="stylesheet" href="stile-principale.css">
        <link rel="stylesheet" href="stile-contatti.css">
        <script src="script-menuMobile.js" defer></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@1&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital@1&display=swap" rel="stylesheet">
    </head>
    <body>   
        <header id="header-sito">
            <section id="menu-pochi-pixel">
                    <div id="nav-conteiner" class="hidden"></div>
                    <div id="bottone">
                        <div class="mini-menu"></div>
                        <div class="mini-menu"></div>
                        <div class="mini-menu"></div>
                    </div>
            </section>
            <h1>Ludoteca</h1>
            <section id="menu">
                <a id="login" href= <?php echo (isset($_SESSION["userNameLudoteca"])) ?  "aPage-infoProfilo.php" : "aPage-login.php?urlChiamata=aPage-contatti.php"; ?>>
                                    <?php echo (isset($_SESSION["userNameLudoteca"])) ? "Ciao ".$_SESSION["userNameLudoteca"].'!' : "Login"; ?></a>
                </br>
                <nav> 
                    <a href="aPage-home.php">Home</a> 
                    <a href="aPage-eventi.php">Eventi</a>
                    <a href="aPage-classifica.php">Classifica</a> 
                    <a id="paginaAttuale">CONTATTI</a>
                </nav>
            </section>
            <div class="overlay"></div>
        </header>
        <ul id="titolo">
            <li>Contatti</li>
        </ul>
        <section id="descrizione">
            <p>
                Numero di telefono: +39 0951234567 </br>
                E-mail: ludoteca@bucchieri.it </br>
                Indirizzo: via ludoteca 27, 95000, catania </br> </br>
                Aperti tutti i giorni dalle 9.00 alle 21.30 orario continuato.
            </p>
        </section>
        <div id="distanziatore"></div>
        <div id="footerConteiner">
            <footer> 
                <p>Davide bucchieri o46002072</p> <div class="overlay"></div>
            </footer>
        </div>
    </body>
</html>