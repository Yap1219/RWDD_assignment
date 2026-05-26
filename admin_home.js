const burgerBtn = document.getElementById('burgerBtn');
const mobileMenu = document.getElementById('mobileMenu');

burgerBtn.addEventListener('click', () => {
  const isOpen = mobileMenu.classList.toggle('is-open');
  burgerBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
});

mobileMenu.addEventListener('click', (e) => {
  if (e.target.classList.contains('nav__link')) {
    mobileMenu.classList.remove('is-open');
    burgerBtn.setAttribute('aria-expanded', 'false');
  }
});