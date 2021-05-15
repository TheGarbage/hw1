<?php 
    if(!isset($_GET['categoria']) || ($_GET['categoria'] !== "Fps" && $_GET['categoria'] !== "Arcade" && $_GET['categoria'] !== "Quiz" && 
       $_GET['categoria'] !== "Corsa" && $_GET['categoria'] !== "VediTutto")) 
            echo json_encode(array(array('categoriaRisultati' => $_GET['categoria']), array('risposta' => "Errore get, riprovare")));
    else{
        require ('db-config.php');
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
        if(!$conn){
            echo json_encode(array(array('categoriaRisultati' => $_GET['categoria']), array('risposta' => "Errore connessione database, riprovare")));
            exit;
        }
        $categoria = mysqli_real_escape_string($conn, $_GET["categoria"]);
        if($_GET['categoria'] !== "VediTutto")
            $query = "SELECT * FROM videogioco v JOIN ".$_GET['categoria']." c ON (v.codice=c.codice)";
        else
            $query =  "SELECT v.Codice, Titolo, Pegi, Genere, Record_uccisioni_partita, Record_punteggio, Tipo_gara, Tempo_record, Argomento, N_domande
                                FROM videogioco v LEFT JOIN Fps f ON (v.codice=f.codice)
                                                  LEFT JOIN Arcade a ON (v.codice=a.codice)
                                                  LEFT JOIN Corsa c ON (v.codice=c.codice)
                                                  LEFT JOIN Quiz q ON (v.codice=q.codice)";
            
        $res = mysqli_query($conn, $query);
        mysqli_close($conn);
        if(!$res)
            echo json_encode(array('risposta' => "Errore select, riprovare"));
        else if(mysqli_num_rows($res) > 0){
            $response = array();
            $response[] = array('categoriaRisultati' => $_GET['categoria']);
            $videogiochi = array();
            while($row = mysqli_fetch_assoc($res)){
                if(isset($row['Genere']) && $row['Genere'] != null && isset($row['Record_uccisioni_partita']) && $row['Record_uccisioni_partita'] != null){
                    $videogiochi[] = array('titolo' => $row['Titolo'],
                                           'categoria' => "Fps",
                                           'codice' => $row['Codice'],
                                           'descrizione' => "Pegi: ".$row['Pegi']."</br>Record-Uccisioni: ".$row['Record_uccisioni_partita']."</br>Genere: ".$row['Genere']);
                }
                else if(isset($row['Record_punteggio']) && $row['Record_punteggio'] != null){
                    $videogiochi[] = array('titolo' => $row['Titolo'],
                                           'categoria' => "Arcade",
                                           'codice' => $row['Codice'],
                                           'descrizione' => "Pegi: ".$row['Pegi']."</br>Record-punti: ".$row['Record_punteggio']);
                }
                else if(isset($row['Tipo_gara']) && $row['Tipo_gara'] != null && isset($row['Tempo_record']) && $row['Tempo_record'] != null){
                    $videogiochi[] = array('titolo' => $row['Titolo'],
                                           'categoria' => "Corsa",
                                           'codice' => $row['Codice'],
                                           'descrizione' => "Pegi: ".$row['Pegi']."</br>T-record: ".$row['Tempo_record']."</br>Tipo-gara: ".$row['Tipo_gara']);
                }
                else if(isset($row['Argomento']) && $row['Argomento'] != null && isset($row['N_domande']) && $row['N_domande'] != null){
                    $videogiochi[] = array('titolo' => $row['Titolo'],
                                           'categoria' => "Quiz",
                                           'codice' => $row['Codice'],
                                           'descrizione' => "Pegi: ".$row['Pegi']."</br>N_domande: ".$row['N_domande']."</br>Argomento: ".$row['Argomento']);
                }
            }
            $response[] = array('videogiochi' => $videogiochi);
            echo json_encode($response);
            mysqli_free_result($res);
        }
    }
?>