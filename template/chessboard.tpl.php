<?php

// default board
//$board = Game::boardFromString(Game::DEFAULT_BOARD_STRING); //TODO
$board = array(
	'a' => array(new Rook(false), new Pawn(false), "", "", "", "", new Pawn(true), new Rook(true)),
	'b' => array(new Knight(false), new Pawn(false), "", "", "", "", new Pawn(true), new Knight(true)),
	'c' => array(new Bishop(false), new Pawn(false), "", "", "", "", new Pawn(true), new Bishop(true)),
	'd' => array(new Queen(false), new Pawn(false), "", "", "", "", new Pawn(true), new Queen(true)),
	'e' => array(new King(false), new Pawn(false), "", "", "", "", new Pawn(true), new King(true)),
	'f' => array(new Bishop(false), new Pawn(false), "", "", "", "", new Pawn(true), new Bishop(true)),
	'g' => array(new Knight(false), new Pawn(false), "", "", "", "", new Pawn(true), new Knight(true)),
	'h' => array(new Rook(false), new Pawn(false), "", "", "", "", new Pawn(true), new Rook(true)),
	);
// prisons
$board[] = array(new Pawn(true), new Pawn(true), new Bishop(true), new Queen(true));
$board[] = array(new Rook(false), new Knight(false), new Bishop(false), new Pawn(false), new Pawn(false));
// have they performed a castling yet?
$board['whiteCastled'] = false;
$board['blackCastled'] = false;



$whitePlayer = true; //TODO replace this with something like $thisPlayer->white

?>
<div id="chessboard">
	<ol id="own-prison" class="prison">
<?php
foreach($board[(integer)!$whitePlayer] as $chesspiece) {
	echo "<li>".$chesspiece."</li>";
}
?>
	</ol>
	<ol id="opp-prison" class="prison">
<?php
foreach($board[(integer)$whitePlayer] as $chesspiece) {
	echo "<li>".$chesspiece."</li>";
}
?>
	</ol>
	<table id="chessboardTable">
		<colgroup>
			<col id="numbersColumnLeft" class="numbersColumn"/>
<?php for ($c='A'; $c<='H'; $c++) echo "<col class=\"file$c\" />"; ?>
			<col id="numbersColumnRight" class="numbersColumn"/>
		</colgroup>
		<thead>
			<tr>
				<td></td>
<?php for ($c='A'; $c<='H'; $c++) echo "<th>$c</th>"; ?>
				<td></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td></td>
<?php for ($c='A'; $c<='H'; $c++) echo "<th>$c</th>"; ?>
				<td></td>
			</tr>
		</tfoot>
		<tbody>
<?php // actual board starts here
$light = true;  // altering squares color
for ($i=1; $i<=8; $i++,$light=!$light) {
	$r = $whitePlayer ? 9-$i : $i;  // turn board upside down for black //TODO $whitePlayer
	echo "<tr id=\"rank$r\">\n"
		."\t<th>$r</th>";
	for ($c='A'; $c<='H'; $c++,$light=!$light) {
		echo "<td class=\"square ";
		echo $light ? "light" : "dark";  // altering squares color
		echo "\" id=\"square$r$c\"><span class=\"chesspiece\" id=\"chesspiece$r$c\">"
			.$board[ strtolower($c) ][ $r-1 ]
			."</span></td>";
	}
	echo "<th>$r</th>\n"
		."\t</tr>";
}
?>
		</tbody>
	</table>
</div><!-- #chessboard -->
