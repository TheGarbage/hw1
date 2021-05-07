<?php
    $urlChiamata = (isset($_GET["urlChiamata"])) ? $_GET["urlChiamata"] : "aPage-home.php";
    session_start();
    if(isset($_SESSION["userNameLudoteca"])){
        header("Location: ".$urlChiamata);
        exit;
    }
    if(!empty($_POST["userName"]) && !empty($_POST["passWord"])){
        require ('db-config.php');
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die("Errore: ".mysqli_connect_error());
        $username = mysqli_real_escape_string($conn, $_POST["userName"]);
        $password = mysqli_real_escape_string($conn, $_POST["passWord"]);
        $query = "SELECT * FROM persona WHERE cf = '".$username."' AND password = '".$password."'";
        $res = mysqli_query($conn, $query) or die("Errore: ".mysqli_error($conn));
        mysqli_close($conn);
        if(mysqli_num_rows($res) > 0){
            $_SESSION["userNameLudoteca"] = $_POST["userName"];
            header("Location: ".$urlChiamata);
            mysqli_free_result($res);
            mysqli_close($conn);
            exit;
        } else
            $errore = "Credenziali non valide";
    } 
    else if (isset($_POST["username"]) || isset($_POST["password"]))
        $errore = "Devi compilare tutti i campi";
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Login_Ludoteca</title>
        <link rel="stylesheet" href="stile-login.css">
        <link rel="stylesheet" href="stile-principale.css">
        <script src="script-login.js" defer></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet"> <!-- cancalla se non ti serve -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@1&display=swap" rel="stylesheet"> <!-- cancalla se non ti serve -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital@1&display=swap" rel="stylesheet"> <!-- cancalla se non ti serve -->
    </head>
    <body>  
        <main>
            <form name="login" method="post"> 
                <h2>Bentornato</h2>
                <label <?php if(isset($errore) && ($errore === "Credenziali non valide" || empty($_POST["userName"]))) echo "class=errore"?>
                    >Username: <input type="text" name="userName" class='barraInput' <?php if(!empty($_POST["userName"])) echo "value=".$_POST["userName"]?>></label>
                <label <?php if(isset($errore) && ($errore === "Credenziali non valide" || empty($_POST["passWord"]))) echo "class=errore"?>
                    >Password: <input type="password" name="passWord" class='barraInput' <?php if(!empty($_POST["passWord"])) echo "value=".$_POST["passWord"]?>></label>
                <label>&nbsp<input type='submit' class="submit"></label>
                <p class="pointer">Se non hai un account registrati!</p>
            </form>
        </main>
        <p class='errore'> <?php if(isset($errore)) echo  $errore; ?></p>
    </body>
</html>