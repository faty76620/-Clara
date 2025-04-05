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

//AFFICHAGES DES SECTIONS 
function showTab(tabId) {
    // Masquer toutes les sections
    document.querySelectorAll('.tab-content').forEach(function(section) {
    section.classList.remove('active');
    section.style.display = 'none';
  });
    // Retirer l'état actif de tous les boutons d'onglet
    document.querySelectorAll('.tab-button').forEach(function(btn) {
    btn.classList.remove('active');
  });
    // Afficher la section sélectionnée et activer le bouton correspondant
    document.getElementById(tabId).classList.add('active');
    document.getElementById(tabId).style.display = 'block';
    // Pour le bouton, ajout de class active 
    if (tabId === 'pending') {
    document.getElementById('tab-pending').classList.add('active');
    } else if (tabId === 'approved') {
    document.getElementById('tab-approved').classList.add('active');
    } else if (tabId === 'rejected') {
    document.getElementById('tab-rejected').classList.add('active');
  }
}







