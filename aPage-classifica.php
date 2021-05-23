<?php 
    session_start();
    if(isset($_SESSION["userNameLudoteca"])){
        require('db-config.php');
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
        if(!$conn)
            $responso = "<p class='errore'> Errore connessione database utente, riprovare </p>";
        else{
            $username = mysqli_real_escape_string($conn, $_SESSION["userNameLudoteca"]);
            $query = "SELECT ((SELECT count(*) FROM vista1)-(SELECT count(*) FROM vista1 v2 WHERE v1.punti_totali > v2.punti_totali AND v1.cf<>v2.cf)) AS posizione,  v1.cf, v1.punti_totali, year(v1.anno_nascita), v1.media_punteggio, v1.media_sconto
                    From vista1 v1
                    WHERE cf='".$username."'";
            $res = mysqli_query($conn, $query);
            mysqli_close($conn);
            if(!$res)
                $responso = "<p class='errore'> Errore lettura dati utente, riprovare </p>";
            else{
                $utente = mysqli_fetch_assoc($res);
                mysqli_free_result($res);
                $responso = "<table> <caption>- - -  LA TUA POSIZIONE  - - -</caption>
                            <thead> <tr> <th>N</th> <th>Username</th> <th>Anno Nascita</th> <th>Punteggio Medio</th> <th>Sconto Medio</th> <th>Punti Totali</th> </tr> </thead> <tbody>
                            <tr> <th>".$utente['posizione']."</th> <th>".$utente['cf']."</th> <th>".$utente['year(v1.anno_nascita)']."</th> <th>".$utente['media_punteggio']."</th> <th>".$utente['media_sconto']."</th> <th>".$utente['punti_totali']."</th></tr>
                            </tbody> </table>";
            }
        }
    }
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Ludoteca_classifica</title>
        <link rel="stylesheet" href="stile-principale.css">
        <link rel="stylesheet" href="stile-classifica.css">
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
                <a id="login" href= <?php echo (isset($_SESSION["userNameLudoteca"])) ?  "aPage-infoProfilo.php" : "aPage-login.php?urlChiamata=aPage-classifica.php"; ?>>
                                    <?php echo (isset($_SESSION["userNameLudoteca"])) ? "Ciao ".$_SESSION["userNameLudoteca"].'!' : "Login"; ?></a>
                </br>
                <nav>   
                    <a href="aPage-home.php">Home</a> 
                    <a href="aPage-eventi.php">Eventi</a>
                    <a id="paginaAttuale">CLASSIFICA</a>
                    <a href="aPage-contatti.php">Contatti</a> 
                </nav>
            </section>
            <div class="overlay"></div>
        </header>
        <ul id="titolo">
            <li>Classifica</li>
        </ul>
        <section id="descrizione">
            <p>
                Ecco i 100 giocatori che sono riusciti a conquistare più punti di tutti e quindi anche maggior sconto.
                Bisogna avere almeno un punto per essere classificato. Lotta per rientrarci!
            </p>
        </section>
        <?php 
            if(isset($_SESSION["userNameLudoteca"])) 
                echo $responso;
            require ('db-config.php');
            $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
            if(!$conn)
                echo "<p class='errore'> Errore connessione database generale, riprovare </p>";
            else{
                $query = "CALL proc2(100)";
                $res = mysqli_query($conn, $query);
                mysqli_close($conn);
                if(!$res)
                    echo "<p class='errore'> Errore lettura dati classifica, riprovare </p>";
                else{
                    $tabella = "<table> <caption>- - -  TOP 100  - - -</caption>
                                <thead> <tr> <th>N</th> <th>Username</th> <th>Anno Nascita</th> <th>Punteggio Medio</th> <th>Sconto Medio</th> <th>Punti Totali</th> </tr> </thead> <tbody>";
                    while($utente = mysqli_fetch_assoc($res))
                        if($utente['punti_totali'] !== '0')
                            $tabella .= "<tr> <th>".$utente['posizione']."</th> <th>".$utente['cf']."</th> <th>".$utente['anno_nascita']."</th> <th>".$utente['media_punteggio']."</th> <th>".$utente['media_sconto']."</th> <th>".$utente['punti_totali']."</th></tr>";
                    $tabella .= "</tbody> </table>";
                    mysqli_free_result($res);
                    echo $tabella;
                }
            }
        ?>
        <div id="distanziatore"></div>
        <div id="footerConteiner">
            <footer class="sito-principale"> 
                <p>Davide bucchieri o46002072</p> <div class="overlay"></div>
            </footer>
        </div>
    </body>
</html>