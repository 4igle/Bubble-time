if (screen.width <= 1200 || window.matchMedia("only screen and (max-width: 1200px)").matches)
{
	let div = document.getElementById("alone");
	const p = document.createElement("p");
	p.classList.add("avertissement");
	p.innerHTML = '<a href="accueil.php" style="color: white;"><br>Retour</a>'
	p.style.textAlign = "center";
	div.appendChild(p);
}