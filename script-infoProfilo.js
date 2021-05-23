function riApriModifica(event){
    const div = event.currentTarget.parentNode;
    const label = div.parentNode;
    div.classList.add('hidden');
    for(item of label.querySelectorAll('input'))
        item.classList.remove('hidden');
}

function inviaDati(event){
    event.preventDefault();
    const label = event.currentTarget.querySelector('label');
    const div = label.querySelector('div');
    const inputs = label.querySelectorAll('input');
    for(item of inputs)
        item.classList.add('hidden');
    div.querySelector('p').textContent = inputs[0].value;
    div.classList.remove('hidden');
}

function apriModifica(event){
    const div = event.currentTarget.parentNode;
    const label = div.parentNode;
    div.classList.add('hidden');
    const input = document.createElement('input');
    input.name = div.dataset.name;
    input.type = (div.dataset.name !== 'data') ? "text":"date";
    input.value = div.querySelector('p').textContent;
    input.dataset.max = div.dataset.max;
    input.classList.add('barraInput');
    label.appendChild(input);
    const submit = document.createElement('input');
    submit.type = 'submit';
    submit.classList.add('submit');
    label.appendChild(submit);
    submit.parentNode.parentNode.addEventListener('submit', inviaDati);
    event.currentTarget.removeEventListener('click', apriModifica);
    event.currentTarget.addEventListener('click', riApriModifica);
}

function riApriModificaPassword(event){
    const pulsante = event.currentTarget;
    pulsante.classList.add('hidden');
    pulsante.parentNode.querySelector('form').classList.remove('hidden');
}

function cambiaPassword(event){
    event.preventDefault();
    form = event.currentTarget;
    form.parentNode.querySelector('div').classList.remove('hidden');
    form.classList.add('hidden');
}

function apriModificaPassword(event){
    const pulsante = event.currentTarget;
    pulsante.classList.add('hidden');
    pulsante.removeEventListener("click", apriModificaPassword);
    pulsante.addEventListener("click", riApriModificaPassword);
    const section = event.currentTarget.parentNode;
    const form = document.createElement('form');
    form.name = "password";
    form.method = "post";
    form.addEventListener('submit', cambiaPassword);
    section.appendChild(form);
    const label1 = document.createElement('label');
    label1.textContent = "Vecchia password: ";
    form.appendChild(label1);
    const input1 = document.createElement('input');
    input1.name = "vecchiaPassword";
    input1.type = "password";
    input1.classList.add('barraInput');
    label1.appendChild(input1);
    const label2 = document.createElement('label');
    label2.textContent = "Nuova password: ";
    form.appendChild(label2);
    const input2 = document.createElement('input');
    input2.name = "nuovaPassword";
    input2.type = "password";
    input2.classList.add('barraInput');
    label2.appendChild(input2);
    const label3 = document.createElement('label');
    label3.textContent = "Conferma password: ";
    form.appendChild(label3);
    const input3 = document.createElement('input');
    input3.name = "confermaPassword";
    input3.type = "password";
    input3.classList.add('barraInput');
    label3.appendChild(input3);
    const label4 = document.createElement('label');
    label4.classList.add('submitLabel');
    label4.innerHTML = "&nbsp";
    form.appendChild(label4);
    const input4 = document.createElement('input');
    input4.type = "submit";
    input4.classList.add('submit');
    input4.classList.add('submitPassword');
    label4.appendChild(input4);
}

for(item of document.querySelectorAll('img.pointer'))    
    item.addEventListener('click', apriModifica);
document.querySelector("[data-name='password']").addEventListener("click", apriModificaPassword);