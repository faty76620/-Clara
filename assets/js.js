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

 // VÉRIFICATION DU MOT DE PASSE AVANT SOUMISSION
 document.querySelector("form").addEventListener("submit", function(e) {
  let password = document.getElementById("password").value;
  let confirmPassword = document.getElementById("confirm_password").value;

  if (password !== confirmPassword) {
      e.preventDefault();
      alert("Les mots de passe ne correspondent pas !");
  }
});
