const personalButton = document.querySelector('.personalButton');
const medicoButton = document.querySelector('.medicoButton');
const saludButton = document.querySelector('.cenSaludButton');
const emergenciaButton= document.querySelector('.emergenciaButton');
const farmaceuticaButton = document.querySelector('.farmaceuticaButton');


const tablePerson = document.querySelector('.personalTable');
const tableMed = document.querySelector('.medTable');
const tableSalud = document.querySelector('.centSalTable');
const tableEmerg = document.querySelector('.emergenciaTable');
const tableFarmaceutica = document.querySelector('.farmaceuticaTable');


const emergText = document.querySelector('.emergenciaText');
const farmText = document.querySelector('.farmText')


personalButton.addEventListener('click', (e)=>{
    e.preventDefault();
    tablePerson.style.display = 'table';
    tableMed.style.display = 'none';
    tableSalud.style.display = 'none';
    tableEmerg.style.display = 'none';
    tableFarmaceutica.style.display = 'none';

    emergText.style.display = 'none';
    farmText.style.display = 'none';
});

medicoButton.addEventListener('click', (e)=>{
    e.preventDefault();
    tableMed.style.display = 'table';
    tablePerson.style.display = 'none';
    tableSalud.style.display = 'none';
    tableEmerg.style.display = 'none';
    tableFarmaceutica.style.display = 'none';

    emergText.style.display = 'none';
    farmText.style.display = 'none';
});
saludButton.addEventListener('click', (e)=>{
    e.preventDefault();
    tableSalud.style.display = 'table';
    tablePerson.style.display = 'none';
    tableMed.style.display = 'none';
    tableEmerg.style.display = 'none';
    tableFarmaceutica.style.display = 'none';

    emergText.style.display = 'none';
    farmText.style.display = 'none';
});
emergenciaButton.addEventListener('click', (e)=>{
    e.preventDefault();
    tableEmerg.style.display = 'inline-table';
    emergText.style.display = 'block';
    tableSalud.style.display = 'none';
    tablePerson.style.display = 'none';
    tableMed.style.display = 'none';
    tableFarmaceutica.style.display = 'none';

    farmText.style.display = 'none';
});
farmaceuticaButton.addEventListener('click', (e)=>{
    e.preventDefault();
    tableFarmaceutica.style.display = 'table';
    farmText.style.display = 'block';
    tableSalud.style.display = 'none';
    tablePerson.style.display = 'none';
    tableMed.style.display = 'none';
    tableEmerg.style.display = 'none';

    emergText.style.display = 'none';
});


// .style.display = 'table';
// .style.display = 'none';
