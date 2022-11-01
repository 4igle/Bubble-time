if (screen.width <= 1200 || window.matchMedia("only screen and (max-width: 1200px)").matches)
{
	let gauche = document.querySelector("div.discussions");
	let tout = document.querySelector("div.tout");
	let bandeau = document.querySelector("div.bandeau");
	const retour = document.createElement("p");
	retour.innerHTML = '<a href="accueil.php" class="retour">Retour</a>';
	tout.removeChild(gauche);
	bandeau.appendChild(retour);
	retour.style.position = "absolute";
	retour.style.marginLeft = "20px";
	retour.style.fontFamily = "arial, sans-serif";
	move_down();
}