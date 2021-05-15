
//CONFIGURAZIONE PREFERITI -------------------------------------------------------------------------------------------------------------------------------------------------------
function onJsonPreferiti(preferiti){
    if(preferiti['risposta'] === undefined){
        for(item of preferiti)
            giochiPreferiti.unshift(item);
        if(document.querySelector('.evento') !== null)
            fetch("server-eventi.php").then(onResponse).then(stampaDiNuovo);
    }
    else{
        const errore = document.createElement('p');
        errore.textContent = preferiti['risposta'];
        errore.classList.add('errore');
        document.querySelector('main').appendChild(errore);
    }
}

fetch("server-preferitiLettura.php").then(onResponse).then(onJsonPreferiti);