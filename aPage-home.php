<?php session_start(); ?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Ludoteca_home</title>
        <link rel="stylesheet" href="stile-principale.css">
        <script src=<?php echo (isset($_SESSION["userNameLudoteca"])) ?  "script-homeLoggato.js" : "script-home.js"; ?> defer></script>
        <script src="script-api-banner.js" defer></script>
        <script src="script-menuMobile.js" defer></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@1&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital@1&display=swap" rel="stylesheet">
    </head>
    <body>   
        <div id="body-overlay" class="overlay hidden"></div>
        <header id="header-sito" class="sito-principale">
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
                <a id="login" href= <?php echo (isset($_SESSION["userNameLudoteca"])) ?  "aPage-infoProfilo.php" : "aPage-login.php"; ?>>
                                    <?php echo (isset($_SESSION["userNameLudoteca"])) ? "Ciao ".$_SESSION["userNameLudoteca"].'!' : "Login"; ?></a>
                </br>
                <nav>   
                    <a>Eventi</a> 
                    <a href="aPage-classifica.php">Classifica</a> 
                    <a>Contatti</a> 
                </nav>
            </section>
            <div class="overlay"></div>
        </header>
        <section id="alertNonCreato" class="sito-principale"></section> 
        <section id="descrizione" class="sito-principale">
            <p>Gioca a centinaia di giochi e fai più punti possibili per avere sconti sempre più grandi. Diventa nostro cliente per essere sempre aggiornato sulle novità
                ed entrare nella nostra classifica ufficiale. Dicci quali sono i tuoi giochi preferiti e ti metteremo in evidenzia tutti gli eventi che li riguardano
                cosi che non ti perderai nulla. Dimostra a tutti fino a dove può arrivare il tuo lato nerd!
            </p>
            <a>Scopri di più</a>
        </section>
        <div class="sotto-siti"></div>
        <section id="blocchi">
            <div data-tema="Fps" class="blocco pointer"><h4>Fps</h4><p class='hidden'>Combatti orde di nemici in scenari a tema</p><div class="block-overlay overlay dark-overlay"></div></div>
            <div data-tema="Arcade" class="blocco pointer"><h4>Arcade</h4><p class='hidden'>Gioca ai giochi che hanno fatto la storia</p><div class="block-overlay overlay dark-overlay"></div></div>
            <div data-tema="Quiz" class="blocco pointer"><h4>Quiz</h4><p class='hidden'>Quanto pensi di conoscere bene le cose?</p><div class="block-overlay overlay dark-overlay"></div></div>
            <div data-tema="Corsa" class="blocco pointer" ><h4>Corsa</h4><p class='hidden'>Guida in alcuni dei circuiti più famosi al mondo</p><div class="block-overlay overlay dark-overlay"></div></div>
            <div data-tema="VediTutto" class="blocco pointer"><h4>Tutto</h4><p class='hidden'>Perchè vedere una categoria alla volta?</p><div class="block-overlay overlay dark-overlay"></div></div>
        </section>
        <div id="distanziatore">
        <div id="footerConteiner">
            <footer class="sito-principale"> 
                <p>Davide bucchieri o46002072</p> <div class="overlay"></div>
            </footer>
        </div>
        </div>
    </body>
</html>