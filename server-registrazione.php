<?php 
    if(!isset($_POST['nomeCognome']) || empty($_POST['nomeCognome']) || strlen($_POST['nomeCognome']) > 50  ||
       !isset($_POST['userName']) || empty($_POST['userName']) || strlen($_POST['userName']) > 20 ||
       !isset($_POST['passWord']) || empty($_POST['passWord']) || strlen($_POST['passWord']) < 8 ||
       !isset($_POST['confermaPassword']) || $_POST['passWord'] !== $_POST['confermaPassword'] ||
       !isset($_POST['occupazione']) || empty($_POST['occupazione']) || strlen($_POST['occupazione']) > 30 ||
       !isset($_POST['dataNascita']) || empty($_POST['dataNascita']) || !strtotime($_POST['dataNascita']))
            echo json_encode(array('risposta' => "Errore formattazione, riprovare"));
    else{
        require ('db-config.php');
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
        if(!$conn){
            echo json_encode(array('risposta' => "Errore connessione database, riprovare"));
            exit;
        }
        $username = mysqli_real_escape_string($conn, $_POST["userName"]);
        $query = "SELECT cf FROM persona WHERE cf = '".$username."'";
        $res = mysqli_query($conn, $query);
        if(!$res){
            echo json_encode(array('risposta' => "Errore select, riprovare"));
            mysqli_close($conn);
            exit;
        }
        else if(mysqli_num_rows($res) > 0)
            echo json_encode(array('risposta' => "Username non disponibile"));
        else{
            $password = mysqli_real_escape_string($conn, $_POST["passWord"]);
            $password = password_hash($password, PASSWORD_BCRYPT);
            $nomeCognome = mysqli_real_escape_string($conn, $_POST["nomeCognome"]);
            $data = mysqli_real_escape_string($conn, date('Y-m-d', strtotime($_POST['dataNascita'])));
            $occupazione = mysqli_real_escape_string($conn, $_POST["occupazione"]);
            $query = "INSERT INTO persona(CF, Password, Nome, Anno_nascita, occupazione) VALUES('$username', '$password', '$nomeCognome' ,'$data', '$occupazione')";
            if(!mysqli_query($conn, $query)){
                echo json_encode(array('risposta' => "Errore registrazione, riprovare"));
                exit;
            }
            echo json_encode(array('risposta' => "ok"));
        }
        mysqli_free_result($res);
        mysqli_close($conn);
    }
?>