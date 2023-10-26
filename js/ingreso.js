const openModal = document.querySelector('.buttonLog');
const modal = document.querySelector('.modal');
const closeModal = document.querySelector('.modalClose');

const forgotPswd = document.querySelector('.forgotPswd');
const modalPass = document.querySelector('.modalPass');
const enviarButton = document.querySelector('.enviar');
const tittlePass = document.querySelector('.tittle');
const inputEmail = document.getElementById('emailPass');
const checkTittle = document.querySelector('.check');
const closePassModal = document.querySelector('.modalPassClose');

openModal.addEventListener('click', (e)=>{
    e.preventDefault();
    modal.classList.add('modalShow');
});

closeModal.addEventListener('click', (e)=>{
    e.preventDefault();
    modal.classList.remove('modalShow');
});

forgotPswd.addEventListener('click', (e)=>{
    e.preventDefault();
    modalPass.classList.add('modalPassShow');
    tittlePass.style.display = 'block';
    inputEmail.style.display = 'block';
    checkTittle.style.display = 'none';
});
enviarButton.addEventListener('click', (e)=>{
    e.preventDefault();
    tittlePass.style.display = 'none';
    inputEmail.style.display = 'none';
    enviarButton.style.display = 'none';
    checkTittle.style.display = 'block';
});
closePassModal.addEventListener('click', (e)=>{
    e.preventDefault();
    modalPass.classList.remove('modalPassShow');
});
