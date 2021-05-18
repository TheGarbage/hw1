<?php 
      session_start(); 
      if(!isset($_SESSION["userNameLudoteca"])){
            header("Location: aPage-login.php");
            exit;
      }
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Ludoteca_infoProfilo</title>
        <link rel="stylesheet" href="stile-principale.css">
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
                <nav> 
                    <a href="aPage-home.php">Home</a> 
                    <a href="aPage-eventi.php">Eventi</a> 
                    <a href="aPage-classifica.php">Classifica</a> 
                    <a href="aPage-contatti.php">Contatti</a> 
                </nav>
            </section>
            <div class="overlay"></div>
        </header>
        <section id="descrizione">
            <p>
                Qui puoi vedere tutte le informazioni relative al tuo profilo!
            </p>
        </section>
        <ul id="titolo">
            <li>Modifica profilo</li>
            <li>Giochi preferiti</li>
            <li>Posizione in classifica</li>
            <li>Eventi giochi preferiti</li>
            <li>Ultime 30 transazioni</li>
            <li>Gioco più usato e categoria prederiti</li>
        </ul>
        <a href="aPage-logout.php">Logout<a>
        <div id="distanziatore"></div>
        <div id="footerConteiner">
            <footer> 
                <p>Davide bucchieri o46002072</p> <div class="overlay"></div>
            </footer>
        </div>
    </body>
</html>
