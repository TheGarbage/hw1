<?php session_start(); ?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Ludoteca_eventi</title>
        <link rel="stylesheet" href="stile-principale.css">
        <link rel="stylesheet" href="stile-eventi.css">
        <script src="script-menuMobile.js" defer></script>
        <script src="script-eventi.js" defer></script>
        <?php if(isset($_SESSION["userNameLudoteca"])) echo "<script src='script-preferiti.js' defer></script>"; ?> 
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
                <a id="login" href= <?php echo (isset($_SESSION["userNameLudoteca"])) ?  "aPage-infoProfilo.php" : "aPage-login.php?urlChiamata=aPage-eventi.php"; ?>>
                                    <?php echo (isset($_SESSION["userNameLudoteca"])) ? "Ciao ".$_SESSION["userNameLudoteca"].'!' : "Login"; ?></a>
                </br>
                <nav> 
                    <a href="aPage-home.php">Home</a> 
                    <a id="paginaAttuale">EVENTI</a>
                    <a href="aPage-classifica.php">Classifica</a> 
                    <a href="aPage-contatti.php">Contatti</a> 
                </nav>
            </section>
            <div class="overlay"></div>
        </header>
        <ul id="titolo">
            <li>Eventi</li>
        </ul>
        <section id="descrizione">
            <p>
                Ogni giorno lanceremo senza preavviso eventi temporanei, delle durata di qualche ora, per permettere i giocatori che riusciranno a parteciparvi di guadagnare più punti.
                Gli eventi sono legati ognuno ad un gioco specifico e sono caratterizzati da un modificatore di punti e di difficoltà. Il primo ti permette di prendere più punti senza 
                grossi cambiamenti al gameplay. Il secondo, in genere, ti da più punti bonus ancora ma rendendo il gioco nettamente più complicato. Qui sotto sono elencati gli eventi attivi
                attualmente<?php echo (isset($_SESSION["userNameLudoteca"])) ?  "(Abbiamo evidenziato gli eventi sui tuoi giochi preferiti)." : ". Loggati per avere in evidenzia gli eventi sui tuoi
                giochi preferiti!"; ?>
            </p>
        </section>
        <main></main>
        <div id="distanziatore"></div>
        <div id="footerConteiner">
            <footer> 
                <p>Davide bucchieri o46002072</p> <div class="overlay"></div>
            </footer>
        </div>
    </body>
</html>