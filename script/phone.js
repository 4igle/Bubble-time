if (screen.width <= 1200 || window.matchMedia("only screen and (max-width: 1200px)").matches)
{
	let droite = document.querySelector("div.messages");
	let tout = document.querySelector("div.tout");
	tout.removeChild(droite);
}