<?php 
    session_start();
    if(isset($_GET['codice']) && !(strlen($_GET['codice']) > 100 && !empty($_GET['codice'])) && isset($_SESSION["userNameLudoteca"])){
        require ('db-config.php');
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
        if(!$conn){
            echo json_encode(array('risposta' => "Errore connessione database, riprovare"));
            exit;
        }
        $username = mysqli_real_escape_string($conn, $_SESSION["userNameLudoteca"]);
        $codice = mysqli_real_escape_string($conn, $_GET['codice']);
        $query = "DELETE FROM preferiti WHERE CF='".$username."' && Codice_videogioco=".$codice;
        if(!mysqli_query($conn, $query)){
            echo json_encode(array('risposta' => "Errore cancellazione, riprovare"));
            mysqli_close($conn);
            exit;
        }
        mysqli_close($conn);
        echo json_encode(array('risposta' => "ok"));
    }
    else
        echo json_encode(array('risposta' => "Errore get"));
?>