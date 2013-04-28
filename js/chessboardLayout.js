$(document).ready(setChessboardSize);
$(window).resize(setChessboardSize);

function setChessboardSize() {
	var gh = $('#game').height() - $("#game header").height() - $("#game footer").height();
	var gw = $("#game").innerWidth();
	var a = Math.min(gh,gw-100);

	$("#chessboardTable").height(.9*a);
	$("#chessboardTable").width(.9*a);

	$("#chessboard").css("margin-top", (gh-0.9*a )/2);
	$("#chessboard").css("font-size",0.07*a+"px");
}