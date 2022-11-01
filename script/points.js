
let fixe = document.querySelectorAll('div.conv_msg_msg');
let auto = document.querySelectorAll('div.js');
let points = document.querySelectorAll('div.points');

for (var i = 0; i < 300; i++)
{
	if (auto[i].offsetHeight > fixe[i].offsetHeight)
	{
		points[i].style.display = "inline";
	}
}