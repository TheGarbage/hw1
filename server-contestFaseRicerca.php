<?php 
    session_start();
    if((isset($_GET['default']) || isset($_GET['search'])) && isset($_SESSION["userNameLudoteca"])){
        $curl = curl_init();
        if(isset($_GET['default']))
            curl_setopt($curl, CURLOPT_URL,
                "https://api.rawg.io/api/games?&key=ca7172b5b84a49d1a3afdb1d2edd251e&ordering=-metacritic&page_size=50"
            );
        else
            curl_setopt($curl, CURLOPT_URL,
                "https://api.rawg.io/api/games?&key=ca7172b5b84a49d1a3afdb1d2edd251e&search=".urlencode($_GET['search'])
            );         
        curl_setopt($curl,  CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        echo $result;
    }
    else
        echo json_encode(array('risposta' => "Errore get"));
?>