<?php
$whitePlayer = true; //TODO replace this with something like $thisPlayer->white
// Default Chessboard
$chesspieceBefore = "<span class=\"chesspiece\">";
$chesspieceAfter = "</span>";
$chesspieces = array();
$chesspieces[0] = $chesspieceBefore."&#x2656;".$chesspieceAfter;
$chesspieces[1] = $chesspieceBefore."&#x2658;".$chesspieceAfter;
$chesspieces[2] = $chesspieceBefore."&#x2657;".$chesspieceAfter;
$chesspieces[3] = $chesspieceBefore."&#x2655;".$chesspieceAfter;
$chesspieces[4] = $chesspieceBefore."&#x2654;".$chesspieceAfter;
$chesspieces[5] = $chesspieceBefore."&#x2657;".$chesspieceAfter;
$chesspieces[6] = $chesspieceBefore."&#x2658;".$chesspieceAfter;
$chesspieces[7] = $chesspieceBefore."&#x2656;".$chesspieceAfter;
for ($i=8;$i<=15;$i++) $chesspieces[$i] = $chesspieceBefore."&#x2659;".$chesspieceAfter;
for ($i=16;$i<=47;$i++) $chesspieces[$i] = "";//$chesspieceBefore.$chesspieceAfter;
for ($i=48;$i<=55;$i++) $chesspieces[$i] = $chesspieceBefore."&#x265F;".$chesspieceAfter;
$chesspieces[56] = $chesspieceBefore."&#x265C;".$chesspieceAfter;
$chesspieces[57] = $chesspieceBefore."&#x265E;".$chesspieceAfter;
$chesspieces[58] = $chesspieceBefore."&#x265D;".$chesspieceAfter;
$chesspieces[59] = $chesspieceBefore."&#x265B;".$chesspieceAfter;
$chesspieces[60] = $chesspieceBefore."&#x265A;".$chesspieceAfter;
$chesspieces[61] = $chesspieceBefore."&#x265D;".$chesspieceAfter;
$chesspieces[62] = $chesspieceBefore."&#x265E;".$chesspieceAfter;
$chesspieces[63] = $chesspieceBefore."&#x265C;".$chesspieceAfter;
?>
<div id="chessboard">
	<ol id="own-prison" class="prison">
		<li>&#x265B;</li>
		<li>&#x265D;</li>
		<li>&#x265F;</li>
	</ol>
	<ol id="opp-prison" class="prison">
		<li>&#x2656;</li>
		<li>&#x2658;</li>
		<li>&#x2659;</li>
		<li>&#x2659;</li>
	</ol>
	<table id="chessboardTable">
		<colgroup>
			<col id="numbersColumnLeft" class="numbersColumn"/>
<?php for ($c='A'; $c<'I'; $c++) echo "<col class=\"file\" />"; ?>
			<col id="numbersColumnRight" class="numbersColumn"/>
		</colgroup>
		<thead>
			<tr>
				<td></td>
<?php for ($c='A'; $c<'I'; $c++) echo "<th>$c</th>"; ?>
				<td></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td></td>
<?php for ($c='A'; $c<'I'; $c++) echo "<th>$c</th>"; ?>
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
	for ($c='A'; $c<'I'; $c++,$light=!$light) {
		echo "<td class=\"square ";
		echo $light ? "light" : "dark";  // altering squares color
		echo "\" id=\"square$r$c\">{$chesspieces[ord($c)-65 + ($r-1)*8]}</td>"; //TODO
	}
	echo "<th>$r</th>\n"
		."\t</tr>";
}
?>
		</tbody>
	</table>
</div><!-- #chessboard -->
