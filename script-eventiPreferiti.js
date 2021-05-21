
//CONFIGURAZIONE PREFERITI -------------------------------------------------------------------------------------------------------------------------------------------------------
function onJsonPreferiti(preferiti){
    if(preferiti['risposta'] === undefined){
        for(item of preferiti)
            giochiPreferiti.unshift(item);
        eventi = document.querySelectorAll('.evento')
        if(eventi !== null)
            for(item of eventi)
                if(giochiPreferiti.indexOf(item.dataset.codice) !== -1){
                    const titolo = item.querySelector('h4');
                    const titoloVecchio = titolo.textContent;
                    item.classList.add('eventoPreferito');
                    const stella = document.createElement('img');
                    stella.src = "Immagini/Stella.jpg";
                    stella.classList.add('stellaEvento');
                    titolo.textContent = '';
                    titolo.appendChild(stella);
                    titolo.innerHTML += evento.Titolo;
                }          
    }
    else{
        const errore = document.createElement('p');
        errore.textContent = preferiti['risposta'];
        errore.classList.add('errore');
        document.querySelector('main').appendChild(errore);
    }
}

fetch("server-database.php?comando=letturaPreferiti").then(onResponse).then(onJsonPreferiti);