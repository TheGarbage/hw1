<?php 
    session_start();
    if(isset($_GET['videogioco']) && !(strlen($_GET['videogioco']) > 100 && !empty($_GET['videogioco'])) && isset($_SESSION["userNameLudoteca"])){
        require ('db-config.php');
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
        $username = mysqli_real_escape_string($conn, $_SESSION["userNameLudoteca"]);
        $videogioco = mysqli_real_escape_string($conn, $_GET['videogioco']);
        $query = "INSERT INTO contest(CF, nome_videogioco) VALUES('$username', '$videogioco')";
        if(!mysqli_query($conn, $query)){
            echo json_encode(array('risposta' => "Errore inserimento, riprovare"));
            mysqli_close($conn);
            exit;
        }
        mysqli_close($conn);
        echo json_encode(array('risposta' => "ok"));
    }
    else
        echo json_encode(array('risposta' => "Errore get"));
?>