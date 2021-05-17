<?php session_start(); ?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Ludoteca_scopriDiPiu</title>
        <link rel="stylesheet" href="stile-principale.css">
        <link rel="stylesheet" href="stile-presentazione.css">
        <script src="script-menuMobile.js" defer></script>
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
                <a id="login" href= <?php echo (isset($_SESSION["userNameLudoteca"])) ?  "aPage-infoProfilo.php" : "aPage-login.php?urlChiamata=aPage-scopriDiPiu.php"; ?>>
                                    <?php echo (isset($_SESSION["userNameLudoteca"])) ? "Ciao ".$_SESSION["userNameLudoteca"].'!' : "Login"; ?></a>
                </br>
                <nav> 
                    <a href="aPage-home.php">Home</a> 
                    <a href="aPage-eventi.php">Eventi</a> 
                    <a href="aPage-classifica.php">Classifica</a> 
                    <a href="aPage-contatti.php">Contatti</a> 
                </nav>
            </section>
            <div class="overlay"></div>
        </header>
        <ul id="titolo">
            <li>Presentazione ludoteca</li>
        </ul>
        <section id="descrizione">
            <p>
                Per poter usufruire dei nostri servizi bisogna sapere alcune nozioni base </br>
                All’ingresso vi sarà data una scheda. Non è vostra! Ha una duplice funzione: vi permette di avviare i giochi nelle varie postazioni da gioco e salva il punteggio che via via andrete a fare. Abbiamo un numero di litato di carte, ma dovrebbero bastare per tutti. Se non sarà disponibile al momento dell’arrivo vi metteremo in coda e vi serviremo quando potrete. </br>
                Quando avrete finito di giocare, dovrete riconsegnare la carta così che i nostri operatori potranno caricare il vostro nuovo punteggio nei nostri sistemi e aggiornare la classifica. Inoltre, riceverai uno sconto in base a quanto hai ottenuto. In generale i prezzi consistono in 5 euro l’ora a cui si andrà ad applicare uno sconto più o meno grande in base alla fascia punteggio. Abbiamo attualmente 5 fasce: </br>
                •	<=50 punti 0% </br>
                •	<=100 punti 11.5% </br>
                •	<=150 punti 22.5% </br>
                •	<=300 punti 32.5% </br>
                •	<=500 punti 42.5% </br>
                •	>500 punti 52.5% </br>
                Non preoccupatevi che ogni gioco è calibrato in modo tale da permettervi di raggiungere le varie fasce in egual misura. </br>
                A tal proposito nella nostra ludoteca saranno presenti 4 sale. Ogni sala è adibita ad uno dei 4 generi che vi proponiamo: fps, arcade, corsa e quiz. Abbiamo un vastissimo catalogo di giochi per ogni categoria e faremo di tutto per mantenerlo aggiornato e farvi trovare sempre giochi nuovi e interessanti. </br>
                In fine, ogni giorno lanceremo a sorpresa degli eventi su certi giochi che permettono di ottenere più punti per un periodo limitato andando a volte ad influire sulla difficoltà del gioco (soprattutto se parliamo di alti modificatori di punteggi). Guarda spesso la sezione eventi per non perderti nulla.  </br>
                Detto tutto ciò non ti resta che venirci a trovare e tentare di scalare la nostra classifica!
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