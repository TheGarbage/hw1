<?php 
    if(!isset($_GET['comando']))
        esci("Errore get, comando non inizializzato. Riprovare");
    require ('db-config.php');
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
    if($_GET['comando'] === "sceltaContest" || $_GET['comando'] === "letturaPreferiti" || $_GET['comando'] === "inserimentoPreferiti" || 
    $_GET['comando'] === "eliminazionePreferiti" || $_GET['comando'] === "modificaDati" || $_GET['comando'] === "cambioPassword"){
        session_start();
        if(!isset($_SESSION["userNameLudoteca"])) 
            esci("Loggati per continuare");
        $username = mysqli_real_escape_string($conn, $_SESSION["userNameLudoteca"]);
    }
    switch($_GET['comando']){ //configuro la query
        case "sceltaContest":
            if(!isset($_GET['videogioco']) || empty($_GET['videogioco']))
                esci("Parametro videogioco inesistente o vuoto, riprovare");
            $videogioco = mysqli_real_escape_string($conn, $_GET['videogioco']);
            $query = "INSERT INTO contest(CF, nome_videogioco) VALUES('$username', '$videogioco')";
            break;
        case "eventi":
            $query = "CALL proc4";
            break;
        case "letturaPreferiti":
            $query = "SELECT Codice_videogioco FROM preferiti WHERE cf='".$username."'";
            break;
        case "inserimentoPreferiti":
            if(!isset($_GET['codice']) && empty($_GET['codice']))
                esci("Parametro codice inesistente o vuoto, riprovare");
            $codice = mysqli_real_escape_string($conn, $_GET['codice']);
            $query = "INSERT INTO preferiti(CF, Codice_videogioco) VALUES('$username', '$codice')";
            break;
        case "eliminazionePreferiti":
            if(!isset($_GET['codice']) && empty($_GET['codice']))
                esci("Parametro codice inesistente o vuoto, riprovare");
            $codice = mysqli_real_escape_string($conn, $_GET['codice']);
            $query = "DELETE FROM preferiti WHERE CF='".$username."' && Codice_videogioco=".$codice;
            break;
        case "registrazione":
            if(!isset($_POST['nomeCognome']) || empty($_POST['nomeCognome']) || strlen($_POST['nomeCognome']) > 50  ||
               !isset($_POST['userName']) || empty($_POST['userName']) || strlen($_POST['userName']) > 20 ||
               !isset($_POST['passWord']) || empty($_POST['passWord']) || strlen($_POST['passWord']) < 8 ||
               !isset($_POST['confermaPassword']) || $_POST['passWord'] !== $_POST['confermaPassword'] ||
               !isset($_POST['occupazione']) || empty($_POST['occupazione']) || strlen($_POST['occupazione']) > 30 ||
               !isset($_POST['dataNascita']) || empty($_POST['dataNascita']) || !strtotime($_POST['dataNascita']))
                    esci("Errore formattazione, riprovare");
            $username = mysqli_real_escape_string($conn, $_POST["userName"]);
            $query = "SELECT cf FROM persona WHERE cf = '".$username."'";
            $res = mysqli_query($conn, $query);
            if(!$res){
                mysqli_close($conn);
                esci("Errore select, riprovare");
            }
            else if(mysqli_num_rows($res) > 0){
                mysqli_close($conn);
                esci("Username non disponibile");
            }
            $password = mysqli_real_escape_string($conn, $_POST["passWord"]);
            $password = password_hash($password, PASSWORD_BCRYPT);
            $nomeCognome = mysqli_real_escape_string($conn, $_POST["nomeCognome"]);
            $data = mysqli_real_escape_string($conn, date('Y-m-d', strtotime($_POST['dataNascita'])));
            $occupazione = mysqli_real_escape_string($conn, $_POST["occupazione"]);
            $query = "INSERT INTO persona(CF, Password, Nome, Anno_nascita, occupazione) VALUES('$username', '$password', '$nomeCognome' ,'$data', '$occupazione')";
            break;
        case "modificaDati":
            if(!isset($_GET['chiave']) || empty($_GET['chiave']) || !isset($_GET['valore']) || empty($_GET['valore']))
                esci("Parametri chiave-valore inesistenti o vuoti, riprovare");
            switch ($_GET['chiave']) {
                case "nome": if (strlen($_GET['valore']) <= 50) break;
                case "anno_nascita": if (!(strtotime($_GET['valore']))) break;
                case "occupazione": if (strlen($_GET['valore']) <= 30) break;
                default: esci("Errore formattazione valori get, riprovare");
            }
            if($_GET['valore'] !== "anno_nascita")
                $valore = mysqli_real_escape_string($conn, $_GET["valore"]);
            else
                $valore = mysqli_real_escape_string($conn, date('Y-m-d', strtotime($_GET["valore"])));
            $query = "UPDATE persona SET ".$_GET['chiave']."='".$valore."' WHERE Cf ='".$username."'";
            break;
        case "cambioPassword":
            if(!isset($_GET['vecchiaPassword']) || empty($_GET['vecchiaPassword']) || !isset($_GET['nuovaPassword']) || empty($_GET['nuovaPassword']))
                //|| $_GET['nuovaPassword'] !== $_GET['confermaPassword']);
                esci("Errore formattazione valore get, riprovare");
            $query = "SELECT password FROM persona WHERE cf = '".$username."'";
            $res = mysqli_query($conn, $query);
            if(!$res){
                mysqli_close($conn);
                esci("Errore select, riprovare");
            }
            $row = mysqli_fetch_assoc($res);
            mysqli_free_result($res);
            if (password_verify($_GET['vecchiaPassword'], $row['password'])) {
                $nuovaPassword = mysqli_real_escape_string($conn, $_GET['nuovaPassword']);
                $nuovaPassword = password_hash($nuovaPassword, PASSWORD_BCRYPT);
                $query = "UPDATE persona SET password='".$nuovaPassword."' WHERE Cf ='".$username."'";
            }
            else
                esci("falsaPassword");
            break;
        default:
            esci("Comando sconosciuto, riprovare");
    }
    $res = mysqli_query($conn, $query);
    mysqli_close($conn);
    if(!$res)
        esci("Impossibile completare la query di ".$_GET['comando'].", riprovare");
    $response = array();
    switch($_GET['comando']){ //mando risultati
        case "sceltaContest":
        case "inserimentoPreferiti":
        case "eliminazionePreferiti":
        case "registrazione":
        case "cambioPassword":
            $response = array('risposta' => "ok");
            break;
        case "eventi":
            while($row = mysqli_fetch_assoc($res))
                $response[] = array( 'Titolo' => $row['Titolo'],
                                    'TempoRimasto' => $row['tempo_rimasto'],
                                    'Codice' => $row['codice'],
                                    'Descrizione' => $row['Nome']."</br>Modificatore Bonus: ".$row['Modificatore_bonus']."x</br>Modificatore difficoltà: ".$row['Modificatore_difficolta']."x");
            mysqli_free_result($res);
            break;
        case "letturaPreferiti":
            while($row = mysqli_fetch_assoc($res))
                $response[] = $row['Codice_videogioco'];
            mysqli_free_result($res);
            break;
        case "modificaDati":
            $response = array('risposta' => "ok", 'name' => $_GET['chiave']);
            break;
    }
    echo json_encode($response);

    function esci($errore){
        echo json_encode(array('risposta' => $errore));
        exit;
    }
?>