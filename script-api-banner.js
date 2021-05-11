//Funzione di appoggio -------------------------------------------------------------------------------------------------------------------------------------------------------
function formattaNumero(numero){
    const stringa = numero.toString();
    let stringaFormattata = "";
    for(i = 0; i < stringa.length; i++){
        stringaFormattata+= stringa.charAt(i);
        if((stringa.length - (i + 1))%3 === 0 && (stringa.length - (i + 1)) !== 0)
            stringaFormattata+= '.';
    }
    return stringaFormattata;
}

//Configurazione Iniziale -------------------------------------------------------------------------------------------------------------------------------------------------------
function onJsonVaccini(vacciniJSon){
    const result = vacciniJSon.All;
    const sezione = document.querySelector('#alert');
    const vaccini = document.createElement('div');
    sezione.appendChild(vaccini);
    const infoVaccini = document.createElement('p');
    const percentuale = (result.people_vaccinated / result.population) * 100;
    infoVaccini.innerHTML = "Aggiornamento dati: " + result.updated.split(' ')[0] + '</br>' +
                            "Vaccinazioni: " + formattaNumero(result.people_vaccinated) + " (circa il " + 
                            ((percentuale > 10) ? percentuale.toPrecision(4) : percentuale.toPrecision(3)) + 
                            "% della popolazione)";
    vaccini.appendChild(infoVaccini);
    const infoPopolazione = document.createElement('p'); 
    infoPopolazione.innerHTML = "Popolazione totale: " + '</br>' + formattaNumero(result.population);
    infoPopolazione.classList.add('popolazione');
    vaccini.appendChild(infoPopolazione);
    const barra = document.createElement('div');
    barra.id = 'verde';
    vaccini.appendChild(barra);
    const avanzamentoBarra = document.createElement('div');
    avanzamentoBarra.style.width = ((result.people_vaccinated / result.population) * 100) + '%';
    barra.appendChild(avanzamentoBarra);
    sezione.appendChild(document.querySelector('#contest'));
}

function onJsonCasi(casiJson){
    const result = casiJson.All;
    const date = [];
    for(item in casiJson)
        if(item !== "All")
            date.unshift(casiJson[item].updated);
    date.sort();
    const sezione = document.querySelector('#alertNonCreato');
    sezione.id = "alert";
    const h4 = document.createElement('h4');
    h4.textContent = "Prima di tornare a giocare abbiamo una battaglia da vincere!";
    sezione.appendChild(h4);
    const casi = document.createElement('div');
    sezione.appendChild(casi);
    const infoCasi = document.createElement('p');
    const percentuale = (result.confirmed / result.population) * 100;
    infoCasi.innerHTML = "Aggiornamento dati: " + date[0].split(' ')[0] + '</br>' +
                         "Casi confermati: " + formattaNumero(result.confirmed) + " (circa il " + 
                         ((percentuale > 10) ? percentuale.toPrecision(4) : percentuale.toPrecision(3)) + 
                         "% della popolazione)";
    casi.appendChild(infoCasi);
    const infoPopolazione = document.createElement('p'); 
    infoPopolazione.innerHTML = "Popolazione totale: " + '</br>' + formattaNumero(result.population);
    infoPopolazione.classList.add('popolazione');
    casi.appendChild(infoPopolazione);
    const barra = document.createElement('div');
    barra.id = 'rosso';
    casi.appendChild(barra);
    const avanzamentoBarra = document.createElement('div');
    avanzamentoBarra.style.width = ((result.confirmed / result.population) * 100) + '%';
    barra.appendChild(avanzamentoBarra);
    const p = document.createElement('p');
    p.id = ('contest');
    p.textContent = "Nel frattempo partecipa al nostro ";
    sezione.appendChild(p);
    const link = document.createElement('a');
    link.textContent = "contest!";
    link.href="aPage-contest.php";
    p.appendChild(link);
    fetch("https://covid-api.mmediagroup.fr/v1/vaccines?country=Italy").then(onResponse).then(onJsonVaccini);
}

function onResponse(response){
    return response.json();
}

//Configurazione Iniziale -------------------------------------------------------------------------------------------------------------------------------------------------------
fetch("https://covid-api.mmediagroup.fr/v1/cases?country=Italy").then(onResponse).then(onJsonCasi);
