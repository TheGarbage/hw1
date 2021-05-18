<?php 
    session_start();
    if(isset($_SESSION["userNameLudoteca"])){
        require ('db-config.php');
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
        $username = mysqli_real_escape_string($conn, $_SESSION["userNameLudoteca"]);
        $query = "SELECT Codice_videogioco FROM preferiti WHERE cf='".$username."'";
        $res = mysqli_query($conn, $query);
        if(!$res){
            echo json_encode(array('risposta' => "Errore lettura preferiti, riprovare"));
            mysqli_close($conn);
            exit;
        }
        mysqli_close($conn);
        $response = array();
        while($row = mysqli_fetch_assoc($res))
            $response[] = $row['Codice_videogioco'];
        echo json_encode($response);
        mysqli_free_result($res);
    }
    else
        echo json_encode(array('risposta' => "Sessione non attiva"));
?>