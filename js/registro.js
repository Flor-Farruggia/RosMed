const openSuccess = document.querySelector('.buttonSignIn');
const modalSuccess = document.querySelector('.modalSuccess');
const closeModal = document.querySelector('.modalCloseSuc');

openSuccess.addEventListener('click', (e)=>{
    e.preventDefault();
    modalSuccess.classList.add('modalSuccessShow');
});

closeModal.addEventListener('click', (e)=>{
    e.preventDefault();
    modalSuccess.classList.remove('modalSuccessShow');
});