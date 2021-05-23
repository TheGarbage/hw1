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

for(item of document.querySelectorAll('img.pointer'))    
    item.addEventListener('click', apriModifica);