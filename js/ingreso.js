const openModal = document.querySelector('.buttonLog');
const modal = document.querySelector('.modal');
const closeModal = document.querySelector('.modalClose');

openModal.addEventListener('click', (e)=>{
    e.preventDefault();
    modal.classList.add('modalShow');
});

closeModal.addEventListener('click', (e)=>{
    e.preventDefault();
    modal.classList.remove('modalShow');
});