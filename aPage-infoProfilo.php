<?php 
    session_start(); 
    if(!isset($_SESSION["userNameLudoteca"])){
        header("Location: aPage-login.php");
        exit;
    }
    require ('db-config.php');
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    if(!$conn)
        $errore = " Errore connessione database, riprovare";
    else {
        $username = mysqli_real_escape_string($conn, $_SESSION["userNameLudoteca"]);
        $query = "SELECT cf, nome, anno_nascita, occupazione FROM persona";
        $res = mysqli_query($conn, $query);
        if(!$res)
            $errore = " Errore lettura dati, riprovare";
        else 
            $user = mysqli_fetch_assoc($res);
    }   
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Ludoteca_infoProfilo</title>
        <link rel="stylesheet" href="stile-principale.css">
        <link rel="stylesheet" href="stile-infoProfilo.css">
        <script src="script-menuMobile.js" defer></script>
        <script src="script-infoProfilo.js" defer></script>
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
            <h1>Profilo</h1>
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
                <?php echo "Ciao ".$username.", qui puoi vedere i dettagli del tuo profilo e lo storico transazioni!" ?>
            </p>
        </section>
        <ul id="titolo">
            <li class='infoGenerali'>Info generali
                <?php 
                    echo (isset($errore)) ? "<p class='errore'>".$errore."</p>"
                    :
                        "<main>
                            <section>
                                <form name='nome' method='post'>
                                    <label>Nome e cognome: <div data-name='nome' data-max='50' class='barraInput'><p>".$user['nome']."</p><img class='pointer' src='immagini/modificabile.jpg'></div></label>
                                </form>
                                <label>Username: <div data-name='username' class='barraInput'>".$user['cf']."</div></label> 
                                <form name='anno_nascita' method='post'>
                                    <label>Data nascita: <div data-name='data' class='barraInput'><p>".$user['anno_nascita']."</p><img class='pointer' src='immagini/modificabile.jpg'></div></label>
                                </form>
                                <form name='occupazione' method'post'>
                                    <label>Occupazione: <div data-name='occupazione' data-max='30' class='barraInput'><p>".$user['occupazione']."</p><img class='pointer' src='immagini/modificabile.jpg'></div></label>
                                </form>
                            </section>
                            <section>
                            <div data-name='password' class='pointer'>Cambia password</div>
                            </section>
                        </main>
                        <p class='errore margineRidotto'></p>"
                ?>
            </li>
            <li>Riepilogo transazioni
                <?php 
                    if(!$conn)
                        echo "<p class='errore'> Errore connessione database generale, riprovare </p>";
                    else{
                        $query = "SELECT inizio, fine, TIMEDIFF(fine, inizio) AS durata, punteggio, sconto, spesa FROM cronologia WHERE Cf = '".$username."'";
                        $res = mysqli_query($conn, $query);
                        mysqli_close($conn);
                        if(!$res)
                            echo "<p class='errore'> Errore lettura dati classifica, riprovare </p>";
                        else{
                            $utente = mysqli_fetch_assoc($res);
                            if($utente === null)
                                echo "(0 transazioni effettuate)";
                            else{
                                $spesa = 0;
                                $sconto = 0;
                                $N_righe = 0;
                                $punteggio = 0;
                                $tabella = "<table> <thead> <tr> <th>Orario ingresso</th> <th>Orario uscita</th> <th>Durata sessione</th> <th>Punteggio guadagnato</th> <th>Sconto ottenuto</th> <th>Costo sessione</th> </tr> </thead> <tbody>";
                                do{
                                    $spesa += $utente['spesa'];
                                    $sconto += $utente['sconto'];
                                    $N_righe++;
                                    $punteggio += $utente['punteggio'];
                                    $tabella .= "<tr> <th>".$utente['inizio']."</th> <th>".$utente['fine']."</th> <th>".$utente['durata']."</th> <th>".$utente['punteggio']."</th> <th>".$utente['sconto']."</th> <th>".$utente['spesa']."</th></tr>";
                                }while($utente = mysqli_fetch_assoc($res));
                                $tabella .= "<tr> <th class='nascondi'></th> <th class='nascondi'></th> <th>TOTALE:</th> <th>".$punteggio."</th> <th>".$sconto/$N_righe."</th> <th>".$spesa."</th></tr>";
                                $tabella .= "</tbody> </table>";
                                echo $tabella;
                            }
                            mysqli_free_result($res);
                        }
                    }
                ?>
            </li>
        </ul>
        <a href="aPage-logout.php" id="logout">Logout<a>
        <div id="distanziatore"></div>
        <div id="footerConteiner">
            <footer> 
                <p>Davide bucchieri o46002072</p> <div class="overlay"></div>
            </footer>
        </div>
    </body>
</html>
