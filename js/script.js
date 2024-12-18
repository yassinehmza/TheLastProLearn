function showSection(section) {
    const sections = document.querySelectorAll('section');
    sections.forEach(s => s.classList.remove('visible'));
    document.getElementById(section).classList.add('visible');
}

function toggleMenu() {
    const nav = document.querySelector('nav ul');
    nav.classList.toggle('show');
}
// for about section
function scrollAppear() {
    var introText = document.querySelector('.side-text');
    var sideImage = document.querySelector('.sideImage');
    var introPosition = introText.getBoundingClientRect().top;
    var imagePosition = sideImage.getBoundingClientRect().top;
    
    var screenPosition = window.innerHeight / 1.2;
  
    if(introPosition < screenPosition) {
      introText.classList.add('side-text-appear');
    }
    if(imagePosition < screenPosition) {
      sideImage.classList.add('sideImage-appear');
    }
  }
  window.addEventListener('scroll', scrollAppear);