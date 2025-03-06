//OUVERTURE ET FERMETURE MENU BURGER
function toggleMenu() {
  const menu = document.getElementById("nav-links");
  const burger = document.getElementById("burger");
  
  if (menu.style.right === "0px") {
      menu.style.right = "-200px";
      burger.innerHTML ='<i class="fas fa-bars"></i>' ;
  } else {
      menu.style.right = "0px";
      burger.innerHTML ='<i class="fas fa-xmark"></i>';
  }
}

function hideMenu() {
  document.getElementById("menu").style.right = "-200px";
  document.getElementById("burger").innerHTML = '<i class="fas fa-bars"></i>';
}


//FERMETURE DE L'ALERTE (INFORMATION PROCESSUS INSCRIPTION ADMIN)
document.addEventListener("DOMContentLoaded", function () {
  const alertBox = document.getElementById("alert-info");
  const closeBtn = document.getElementById("close-alert");

  closeBtn.addEventListener("click", function () {
      alertBox.classList.add("fadeOut");
      setTimeout(() => alertBox.style.display = "none", 500);
  });
});





