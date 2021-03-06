<?php 
    session_start(); 
    if(isset($_SESSION["userNameLudoteca"])){
        require('db-config.php');
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
        if(!$conn)
            $errore = "Impossibile connettersi al server, riprovare";
        else{
            $username = mysqli_real_escape_string($conn, $_SESSION['userNameLudoteca']);
            $query = "SELECT * FROM contest WHERE cf = '".$username."'";
            $res = mysqli_query($conn, $query);
            mysqli_close($conn);
            if(!$res)
                $errore = "Errore select, riprovare";
            else if(mysqli_num_rows($res) > 0){
                $rispostaDatabase = mysqli_fetch_assoc($res);
                mysqli_free_result($res);
                if(isset($_GET['elimina']) && $_GET['elimina'] === "true"){
                    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
                    if(!$conn)
                        $errore = "Impossibile connettersi al server per l'eliminazione voto, riprovare";
                    else{
                        $query = "DELETE FROM contest WHERE CF='".$username."'";
                        $res = mysqli_query($conn, $query);
                        mysqli_close($conn);
                        if(!$res)
                            $errore = "Errore eliminazione, riprovare";
                    }
                }
                else 
                    $responso = "Grazie per aver partecipato! </br> Hai selezionato: ".$rispostaDatabase['Nome_videogioco']."
                                </br></br><a href='aPage-contest.php?elimina=true'>Annulla voto</a>";
            }
        }
    }
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Ludoteca_contest</title>
        <link rel="stylesheet" href="stile-principale.css">
        <link rel="stylesheet" href="stile-contest.css">
        <script src="script-menuMobile.js" defer></script>
        <?php if(isset($_SESSION["userNameLudoteca"]) && !isset($errore) && !isset($responso)) echo "<script src='script-api-contest.js' defer></script>" ?>
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
                <a id="login" href= <?php echo (isset($_SESSION["userNameLudoteca"])) ?  "aPage-infoProfilo.php" : "aPage-login.php?urlChiamata=aPage-contest.php"; ?>>
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
            <li>Contest lockdown</li>
        </ul>
        <section id="descrizione">
            <p>
                Abbiamo passato l'ultimo anno chiusi in casa. Quando potremo tornare alla nostra vita quotidiana abbiamo pensato di 
                festeggiare le cosa con un nuovo gioco a tema scelto da voi. Indicateci il vostro gioco preferito o quello che vi ha salvato dalla
                routine post-covid e il pi?? selezionato vincer?? il contest.
            </p>
        </section>
        <?php 
            if(isset($errore)) echo "<p id='responso' class='errore'>".$errore."</p>"; 
            else if (isset($responso)) echo "<p id='responso'>".$responso."</p>"; 
            else echo (isset($_SESSION["userNameLudoteca"])) ?
                "<form id='contestVideogiochi'>
                    <input type='text' id='barraRicerca' class='barraInput' value='Cerca i tuoi giochi:'>
                    <input type='submit' id='submit' class='submit' value='cerca'>
                </form>
                <section id='giochiForm'></section>
                <p id='responso' class='hidden'>Grazie per aver partecipato! </br>
                                                Hai selezionato: 
                </p>" 
                :
                "<p id='responso'> <a href='aPage-login.php?urlChiamata=aPage-contest.php'>Loggati per partecipare!</a></p>" ;
        ?>
        <div id="distanziatore"></div>
        <div id="footerConteiner">
            <footer> 
                <p>Davide bucchieri o46002072</p> <div class="overlay"></div>
            </footer>
        </div>
    </body>
</html>