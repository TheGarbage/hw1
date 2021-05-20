<?php
    $urlChiamata = (isset($_GET["urlChiamata"])) ? $_GET["urlChiamata"] : "aPage-home.php";
    session_start();
    if(isset($_SESSION["userNameLudoteca"])){
        header("Location: ".$urlChiamata);
        exit;
    }
    if(!empty($_POST["userName"]) && !empty($_POST["passWord"])){
        require ('db-config.php');
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
        if(!$conn)
            $errore = "Impossibile connettersi al server, riprovare";
        else{
            $username = mysqli_real_escape_string($conn, $_POST["userName"]);
            $password = mysqli_real_escape_string($conn, $_POST["passWord"]);
            $password = password_hash($password, PASSWORD_BCRYPT);
            $query = "SELECT * FROM persona WHERE cf = '".$username."'";
            $res = mysqli_query($conn, $query);
            mysqli_close($conn);
            if(!$res)
                $errore = "Errore select, riprovare";
            {
                if(mysqli_num_rows($res) > 0){
                    $rispostaDatabase = mysqli_fetch_assoc($res);
                    if (password_verify($_POST['passWord'], $rispostaDatabase['Password'])) {
                        $_SESSION["userNameLudoteca"] = $_POST["userName"];
                        header("Location: ".$urlChiamata);
                    }
                    else
                        $errore = "Credenziali non valide";
                }
                else
                    $errore = "Credenziali non valide";
                mysqli_free_result($res);
            }
        }
    } 
    else if (isset($_POST["username"]) || isset($_POST["password"]))
        $errore = "Devi compilare tutti i campi";
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Ludoteca_Login</title>
        <link rel="stylesheet" href="stile-principale.css">
        <link rel="stylesheet" href="stile-login.css">
        <script src="script-login.js" defer></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@1&display=swap" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital@1&display=swap" rel="stylesheet"> 
    </head>
    <body>  
        <main>
            <form name="login" method="post"> 
                <h2>Bentornato</h2>
                <label <?php if(isset($errore) && ($errore === "Credenziali non valide" || empty($_POST["userName"]))) echo "class=erroreL"?>
                    >Username: <input type="text" name="userName" class='barraInput' data-max="20"<?php if(!empty($_POST["userName"])) echo "value=".$_POST["userName"]?>></label>
                <label <?php if(isset($errore) && ($errore === "Credenziali non valide" || empty($_POST["passWord"]))) echo "class=erroreL"?>
                    >Password: <input type="password" name="passWord" class='barraInput'></label>
                <label>&nbsp<input type='submit' class="submit"></label>
                <p class="pointer">Se non hai un account registrati!</p>
            </form>
        </main>
        <p class='erroreL'><?php if(isset($errore)) echo  $errore; ?></p>
    </body>
</html>