const hamb = document.querySelector('#hamb');
const navbar = document.querySelector('#navbar');

hamb.addEventListener('click',() =>{
    navbar.classList.toggle('active');
    hamb.classList.toggle('active')

});



