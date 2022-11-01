if (screen.width <= 1200 || window.matchMedia("only screen and (max-width: 1200px)").matches)
{
	let logo = document.querySelector("div.logo");
	let main = document.querySelector("div.main");
	main.removeChild(logo);
}