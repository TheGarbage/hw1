//CREAZIONE EVENTI -------------------------------------------------------------------------------------------------------------------------------------------------------
function creaEvento(evento){
    const section = document.createElement('section');
    section.classList.add('evento');
    main.appendChild(section);
    const img = document.createElement('img');
    img.src = "Immagini/" + evento.Titolo + ".jpg";
    section.appendChild(img);
    const div1 = document.createElement('div');
    section.appendChild(div1);
    const div2 = document.createElement('div');
    div1.appendChild(div2);
    const titolo = document.createElement('h4');
    if(giochiPreferiti.indexOf(evento.Codice) !== -1){
        section.classList.add('eventoPreferito');
        const stella = document.createElement('img');
        stella.src = "Immagini/Stella.jpg";
        stella.classList.add('stellaEvento');
        titolo.appendChild(stella);
    }
    titolo.innerHTML += evento.Titolo;
    div2.appendChild(titolo);
    const tempoRimasto = document.createElement('h5');
    tempoRimasto.textContent = evento.TempoRimasto;
    div2.appendChild(tempoRimasto);
    const descrizione = document.createElement('p');
    descrizione.innerHTML = evento.Descrizione;
    div1.appendChild(descrizione);
}

//GESTIONE TEMPO -------------------------------------------------------------------------------------------------------------------------------------------------------
function togliSecondo(){
    const eventi = document.querySelectorAll('.evento');
    let item;
    let newSecondi;
    for(evento of eventi){
        const tempoRimasto = evento.querySelector('h5');
        item = tempoRimasto.textContent.split(':');
        newSecondi = parseInt(item[2]) - 1; 
        if(newSecondi <= 0){
            fetch("server-eventi.php").then(onResponse).then(stampaDiNuovo);
            return;
        }
        else if(newSecondi < 10)
            tempoRimasto.textContent = item[0] + ':' + item[1] + ":0" + newSecondi;
        else 
            tempoRimasto.textContent = item[0] + ':' + item[1] + ':' + newSecondi;
    }
    window.setTimeout("togliSecondo()", 1000);
}

//FUNZIONI FETCH -------------------------------------------------------------------------------------------------------------------------------------------------------
function stampaDiNuovo(json){
    if(json['risposta'] !== undefined){
        const eventi = document.querySelectorAll('.evento');
        for(evento of eventi){
            const tempoRimasto = evento.querySelector('h5');
            tempoRimasto.textContent+= " Errore";
            tempoRimasto.classList.add('errore');
        }
    }
    else{
        main.innerHTML = '';
        for(item of json)
            creaEvento(item);
        window.setTimeout("togliSecondo()", 1000);
    }
}

function stampa(json){
    if(json['risposta'] !== undefined){
        const errore = document.createElement('p');
        errore.textContent = json['risposta'];
        errore.classList.add('errore');
        main.appendChild(errore);
    }
    else{
        for(item of json)
            creaEvento(item);
        }
        window.setTimeout("togliSecondo()", 1000);
}

function onResponse(response){
    return response.json();
}

//CONFIGURAZIONE INIZIALE -------------------------------------------------------------------------------------------------------------------------------------------------------
const main = document.querySelector('main');
const giochiPreferiti = [];
fetch("server-eventi.php").then(onResponse).then(stampa);