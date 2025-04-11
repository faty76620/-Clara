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

function showTab(tabId) {
// Masquer toutes les sections
document.querySelectorAll('.tab-content').forEach(section => {
      section.classList.remove('active');
      section.style.display = 'none';
});

// Enlever la classe active de tous les boutons
document.querySelectorAll('.tab-button').forEach(button => {
      button.classList.remove('active');
});

// Afficher la bonne section
const selectedTab = document.getElementById(tabId);
if (selectedTab) {
      selectedTab.classList.add('active');
      selectedTab.style.display = 'block';
}

// Activer le bouton correspondant
  const buttonId = 'btn-' + tabId;
  const activeBtn = document.getElementById(buttonId);
  if (activeBtn) {
      activeBtn.classList.add('active');
  }
}

// PERMET DE DETAILLER SI OUI OU NON EXEMENS
function toggleRadiologyDetails() {
  const radiologieSelect = document.getElementById('radiologie');
  const details = document.getElementById('radiologie-details');
  details.style.display = (radiologieSelect.value === 'oui') ? 'block' : 'none';
}