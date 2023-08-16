const menuIconoMin = document.querySelector('.icono-menu');
const menuDrop = document.querySelector('.menu-nav-min');


menuIconoMin.addEventListener('click', (e)=>{
    e.preventDefault();
    menuDrop.classList.add('menu-nav-min-show');
});