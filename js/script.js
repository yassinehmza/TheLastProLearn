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
  // QUIZ Page
function quizt(frame) {
    document.getElementById('f1').style='display: none;';
    document.getElementById('f6').style='display: none;';
    document.getElementById('f7').style='display: none;';
    document.getElementById('f8').style='display: none;';
    document.getElementById('f9').style='display: none;';
    document.getElementById('f10').style='display: none;';
    document.getElementById('f11').style='display: none;';
    if(frame == 1) document.getElementById('f1').style = 'display: block';
    else if(frame == 6) document.getElementById('f6').style = 'display: block';
    else if(frame == 7) document.getElementById('f7').style = 'display: block';
    else if(frame == 8) document.getElementById('f8').style = 'display: block';
    else if(frame == 9) document.getElementById('f9').style = 'display: block';
    else if(frame == 10) document.getElementById('f10').style = 'display: block';
    else if(frame == 11) document.getElementById('f11').style = 'display: block'; 
    else alert('error');
  }
  
  function startquiz() {
    document.getElementById('title').style = 'display: none;'; 
  
    document.getElementById('panel').style = 'display: inline-flex;'; 
    document.getElementById('left').style = 'display: block;'; 
    document.getElementById('right').style = 'display: block;'; 
  }
  function searchdisplay() {
    document.getElementById('searchpanel').style.display="block";
  }
  
  function display(n) {
    var img1 = document.getElementById('img1');
    var img2 = document.getElementById('img2');
    var img3 = document.getElementById('img3');
    var img4 = document.getElementById('img4');
    var s1 = document.getElementById('s1');
    var s2 = document.getElementById('s2');
    var s3 = document.getElementById('s3');
    var s4 = document.getElementById('s4');
  
    img1.style = 'display: none;';
    img2.style = 'display: none;';
    img3.style = 'display: none;';
    img4.style = 'display: none;';
    s1.style = 'background: #DF2771; color: #FFF;';
    s2.style = 'background: #DF2771; color: #FFF;';
    s3.style = 'background: #DF2771; color: #FFF;';
    s4.style = 'background: #DF2771; color: #FFF;';
  
    if(n==1) {
      img1.style = 'display: block;';
      s1.style = 'background: #E5E8EF; color: #DF2771;';
    }
    if(n==2) {
      img2.style = 'display: block;';
      s2.style = 'background: #E5E8EF; color: #DF2771;';
    }
    if(n==3) {
      img3.style = 'display: block;';
      s3.style = 'background: #E5E8EF; color: #DF2771;';
    }
    if(n==4) {
      img4.style = 'display: block;';
      s4.style = 'background: #E5E8EF; color: #DF2771;';
    } 
  }
  
  
  function sideMenu(side) {
    var menu = document.getElementById('side-menu');
    if(side==0) {
      menu.style = 'transform: translateX(0vh); position:fixed;';
    }
    else {
      menu.style = 'transform: translateX(-100%);';
    }
    side++;
  }