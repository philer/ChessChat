<?php
//$r = array('A','B','C','D','E','F','G','H');

// Default Chessboard
$chesspieceBefore = "<span class=\"chesspiece\">";
$chessPieceAfter = "</span>";
$chesspieces = array();
$chesspieces[1] = $chesspieceBefore."&#x2656;".$chessPieceAfter;
$chesspieces[2] = $chesspieceBefore."&#x2658;".$chessPieceAfter;
$chesspieces[3] = $chesspieceBefore."&#x2657;".$chessPieceAfter;
$chesspieces[4] = $chesspieceBefore."&#x2655;".$chessPieceAfter;
$chesspieces[5] = $chesspieceBefore."&#x2654;".$chessPieceAfter;
$chesspieces[6] = $chesspieceBefore."&#x2657;".$chessPieceAfter;
$chesspieces[7] = $chesspieceBefore."&#x2658;".$chessPieceAfter;
$chesspieces[8] = $chesspieceBefore."&#x2656;".$chessPieceAfter;
for ($i=9;$i<=16;$i++) {
	$chesspieces[$i] = $chesspieceBefore."&#x2659;".$chessPieceAfter;
}
/*for ($i=17;$i<=48;$i++) {
	$chesspieces[$i] = "";
}*/
for ($i=49;$i<=56;$i++) {
	$chesspieces[$i] = $chesspieceBefore."&#x265F;".$chessPieceAfter;
}
$chesspieces[57] = $chesspieceBefore."&#x265C;".$chessPieceAfter;
$chesspieces[58] = $chesspieceBefore."&#x265E;".$chessPieceAfter;
$chesspieces[59] = $chesspieceBefore."&#x265D;".$chessPieceAfter;
$chesspieces[60] = $chesspieceBefore."&#x265B;".$chessPieceAfter;
$chesspieces[61] = $chesspieceBefore."&#x265A;".$chessPieceAfter;
$chesspieces[62] = $chesspieceBefore."&#x265D;".$chessPieceAfter;
$chesspieces[63] = $chesspieceBefore."&#x265E;".$chessPieceAfter;
$chesspieces[64] = $chesspieceBefore."&#x265C;".$chessPieceAfter;
?>
<div id="chessboard">
	<ol id="own-graveyard" class="graveyard">
		<li>&#x265B;</li>
		<li>&#x265D;</li>
		<li>&#x265F;</li>
	</ol>
	<ol id="opp-graveyard" class="graveyard">
		<li>&#x2656;</li>
		<li>&#x2658;</li>
		<li>&#x2659;</li>
		<li>&#x2659;</li>
	</ol>
	<table id="chessboardTable">
		<colgroup>
			<col id="numbersColumnLeft" class="numbersColumn"/>
<?php for ($c='A'; $c<'I'; $c++) echo "<col id=\"file".$c."\" class=\"file\" />"; ?>
			<col id="numbersColumnRight" class="numbersColumn"/>
		</colgroup>
		<thead>
			<tr>
				<td></td>
<?php for ($c='A'; $c<'I'; $c++) echo "<th>".$c."</th>"; ?>
				<td></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td></td>
<?php for ($c='A'; $c<'I'; $c++) echo "<th>".$c."</th>"; ?>
				<td></td>
			</tr>
		</tfoot>
		<tbody>

<?php
$light = true;
for ($r=8; $r>0; $r--) { ?>
			<tr id="rank<?php echo $r ?>">
				<th><?php echo $r ?></th>
	<?php for ($c=1; $c<=8;$c++) {
		if ($light) { ?>
				<td class="square light"><?php echo $chesspieces[$c+($r-1)*8] ?></td>
		<?php } else { ?>
				<td class="square dark"><?php echo $chesspieces[$c+($r-1)*8] ?></td>
		<?php }
		$light = !$light;
	}
	$light = !$light; ?>
				<th><?php echo $r ?></th>
			</tr>
<?php } ?>

		</tbody>
	</table>
</div><!-- #chessboard -->