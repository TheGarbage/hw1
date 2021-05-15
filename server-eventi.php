<?php 
    require ('db-config.php');
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    if(!$conn){
        echo json_encode(array('risposta' => "Errore connessione database, riprovare"));
        exit;
    }
    $query = "CALL proc4";
    $res = mysqli_query($conn, $query);
    if(!$res){
        echo json_encode(array('risposta' => "Errore lettura eventi, riprovare"));
        mysqli_close($conn);
        exit;
    }
    mysqli_close($conn);
    $response = array();
    while($row = mysqli_fetch_assoc($res))
         $response[] = array( 'Titolo' => $row['Titolo'],
                              'TempoRimasto' => $row['tempo_rimasto'],
                              'Codice' => $row['codice'],
                              'Descrizione' => $row['Nome']."</br>Modificatore Bonus: ".$row['Modificatore_bonus']."x</br>Modificatore difficoltà: ".$row['Modificatore_difficolta']."x");
    echo json_encode($response);
    mysqli_free_result($res);
?>