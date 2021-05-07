//FUNZIONI CONTROLLO -------------------------------------------------------------------------------------------------------------------------------------------------------
function controlloContenutoLogin(event){
    if(form.userName.value.length === 0 ||
       form.passWord.value.length === 0){
           document.querySelector('p.errore').textContent = "Devi compilare tutti i campi";
           if(form.userName.value.length == 0) form.userName.parentNode.classList.add('errore');
           if(form.passWord.value.length == 0) form.passWord.parentNode.classList.add('errore');
           event.preventDefault();
    }
}

function rimuoviErrore(event){
    const input = event.currentTarget;
    if(input.value.length !== 0 && input.parentNode.classList.contains('errore')){
        input.parentNode.classList.remove('errore');
        if(document.querySelector('label.errore') === null)
            document.querySelector('p.errore').textContent = "";
    }
    else if(input.value.length === 0 && !input.parentNode.classList.contains('errore')){
        input.parentNode.classList.add('errore');
        document.querySelector('p.errore').textContent = "Non lasciare indietro campi vuoti";
    }
}

//SWITCH REGISTRAZIONE LOGIN -------------------------------------------------------------------------------------------------------------------------------------------------------
function rimuoviErrori(event){
    const p = event.currentTarget;
    const main = p.parentNode.parentNode;
    if(main.classList.contains('erroreForm')){
        main.classList.remove('erroreForm');
        for(item of main.querySelectorAll('label')){
            if(item.classList.contains('errore'))
                item.classList.remove('errore');
        }
        document.querySelector('.errore').remove();
    }
}

function registrazioneBis(event){
    const p = event.currentTarget;
    const form = p.parentNode;
    const titolo = form.querySelector("h2");
    for(item of form.querySelectorAll('[data-registrazione]'))
        item.classList.remove('hidden');
    p.textContent = "Se hai gia` un account loggati";
    titolo.textContent = "Benvenuto";
    p.removeEventListener('click', registrazioneBis);
    p.addEventListener('click', login);
}

function login(event){
    const p = event.currentTarget;
    const form = p.parentNode;
    const titolo = form.querySelector("h2");
    for(item of form.querySelectorAll('[data-registrazione]'))
        item.classList.add('hidden');
    p.textContent = "Se non hai un account registrati!";
    titolo.textContent = "Bentornato";
    p.removeEventListener('click', login);
    p.addEventListener('click', registrazioneBis);
}

function registrazione(event){
    const p = event.currentTarget;
    const form = p.parentNode;
    const titolo = form.querySelector("h2");
    const confermaPassword = document.createElement('label');
    confermaPassword.textContent = "Conferma Password";
    confermaPassword.dataset.registrazione = "si";
    const confermaPasswordInput = document.createElement('input');
    confermaPasswordInput.name = "confermaPassword";
    confermaPasswordInput.type = "password";
    confermaPasswordInput.classList.add('barraInput');
    confermaPassword.appendChild(confermaPasswordInput);
    form.appendChild(confermaPassword);
    const occupazione= document.createElement('label');
    occupazione.textContent = "Occupazione";
    occupazione.dataset.registrazione = "si";
    const occupazioneInput = document.createElement('input');
    occupazioneInput.name = "confermaPassword";
    occupazioneInput.type = "text";
    occupazioneInput.classList.add('barraInput');
    occupazione.appendChild(occupazioneInput);
    form.appendChild(occupazione);
    const giornoNascita = document.createElement('label');
    giornoNascita.textContent = "Giorno nascita";
    giornoNascita.dataset.registrazione = "si";
    const giornoNascitaInput = document.createElement('input');
    giornoNascitaInput.name = "confermaPassword";
    giornoNascitaInput.type = "date";
    giornoNascitaInput.classList.add('barraInput');
    giornoNascita.appendChild(giornoNascitaInput);
    form.appendChild(giornoNascita);
    form.appendChild(form.querySelector('.submit').parentNode);
    p.textContent = "Se hai gia` un account loggati";
    form.appendChild(p);
    titolo.textContent = "Benvenuto";
    p.removeEventListener('click', registrazione);
    p.addEventListener('click', login);
}

//CONFIGURAZIONE INIZIALE -------------------------------------------------------------------------------------------------------------------------------------------------------
const form = document.forms['login'];
form.addEventListener('submit', controlloContenutoLogin);
form.userName.addEventListener('blur', rimuoviErrore);
form.passWord.addEventListener('blur', rimuoviErrore);
const p = document.querySelector('p');
p.addEventListener('click', registrazione);
p.addEventListener('click', rimuoviErrori);
