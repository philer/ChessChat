<?php

// default board
//$board = Game::boardFromString(Game::DEFAULT_BOARD_STRING);
$board = $this->var['game']->board;

$whitePlayer = $this->var['game']->isWhitePlayer()
			|| !$this->var['game']->isPlayer();

?>
<div id="chessboard">
	<ol id="whitePrison" class="prison white <?php echo $whitePlayer ? 'own' : 'opp'; ?>-prison">
<?php foreach ($board['x'] as $chesspiece) echo "<li>{$chesspiece}</li>"; ?>
	</ol>
	<ol id="blackPrison" class="prison black <?php echo $whitePlayer ? 'opp' : 'own'; ?>-prison">
<?php foreach ($board['y'] as $chesspiece) echo "<li>{$chesspiece}</li>"; ?>
	</ol>
	<table id="chessboardTable">
		<colgroup>
			<col id="numbersColumnLeft" class="numbersColumn"/>
<?php for ($c='A'; $c<='H'; $c++) echo "<col id=\"file{$c}\" class=\"file\"/>"; ?>
			<col id="numbersColumnRight" class="numbersColumn"/>
		</colgroup>
		<thead>
			<tr>
				<td></td>
<?php for ($c='A'; $c<='H'; $c++) echo "<th>{$c}</th>"; ?>
				<td></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td></td>
<?php for ($c='A'; $c<='H'; $c++) echo "<th>{$c}</th>"; ?>
				<td></td>
			</tr>
		</tfoot>
		<tbody>
<?php // actual board starts here
$light = true;  // altering squares color
for ($i=1; $i<=8; $i++,$light=!$light) {
	$r = $whitePlayer ? 9-$i : $i;  // turn board upside down for black
	echo "<tr id=\"rank{$r}\" class=\"rank\">\n"
		."\t<th>{$r}</th>";
	for ($c='A'; $c<='H'; $c++,$light=!$light) {
		echo "<td class=\"square "
			.($light ? "light" : "dark")  // altering squares color
			."\" id=\"square-{$c}{$r}\"><div>";
		if (!is_null($board[strtolower($c)][$r])) {
			echo "<span class=\"chesspiece "
				.($board[strtolower($c)][$r]->isWhite() ? 'white' : 'black' )
				."\" id=\"chesspiece-{$c}{$r}\">"
				.$board[strtolower($c)][$r]
				."</span>";
		}	
		echo "</div></td>";
	}
	echo "<th>{$r}</th>\n"
		."\t</tr>";
}
?>
		</tbody>
	</table>
</div><!-- #chessboard -->
