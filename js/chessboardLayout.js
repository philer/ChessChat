window.addEventListener("load",setChessboardSize,false);
window.addEventListener("resize",setChessboardSize,false);

function setChessboardSize() {
	var gh = document.getElementById("game").offsetHeight
			- document.getElementById("gameMenu").offsetHeight
			- document.getElementById("clock").offsetHeight;
	var gw = document.getElementById("game").offsetWidth;
	var a = Math.min(gh,gw-100);
	
	var cbt = document.getElementById("chessboardTable");
	cbt.style.height = 0.9*a + "px";
	cbt.style.width = 0.9*a + "px";
	
	var cb = document.getElementById("chessboard");
	cb.style.marginTop = (gh-0.9*a )/2 +"px";
	
	var jscss = document.getElementById("jscss");
	jscss.innerHTML = "td.square {font-size:"+0.07*a+"px;}";
}