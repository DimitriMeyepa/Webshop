const hamburger = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobile-menu');

hamburger.addEventListener('click', () => {
  if (mobileMenu.classList.contains('max-h-0')) {
    mobileMenu.classList.remove('max-h-0');
    mobileMenu.classList.add('max-h-screen'); // ouvre le menu
  } else {
    mobileMenu.classList.remove('max-h-screen');
    mobileMenu.classList.add('max-h-0'); // ferme le menu
  }
});
