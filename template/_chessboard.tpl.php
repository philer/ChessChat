<?php

// default board
//$board = Game::boardFromString(Game::DEFAULT_BOARD_STRING);
$board = $this->var['game']->board;

$whitePlayer = $this->var['game']->isWhitePlayer()
            || !$this->var['game']->isPlayer();

// turn board upside down for black
if ($whitePlayer) {
    $ranks = array( 8 , 7 , 6 , 5 , 4 , 3 , 2 , 1 );
    $files = array('A','B','C','D','E','F','G','H');
} else {
    $ranks = array( 1 , 2 , 3 , 4 , 5 , 6 , 7 , 8 );
    $files = array('H','G','F','E','D','C','B','A');
}

?>
<div id="chessboard">
    <ol id="whitePrison" class="prison white <?php echo $whitePlayer ? 'own' : 'opp'; ?>-prison"><?php
foreach ($board->getWhitePrison() as $chesspiece) {
    echo '<li class="chesspiece">'
       . $chesspiece
       . '</li>';
}
    ?></ol>
    <ol id="blackPrison" class="prison black <?php echo $whitePlayer ? 'opp' : 'own'; ?>-prison"><?php
foreach ($board->getBlackPrison() as $chesspiece) {
    echo '<li class="chesspiece">'
       . $chesspiece
       . '</li>';
}
    ?></ol>
    <table id="chessboardTable">
        <colgroup>
            <col id="numbersColumnLeft" class="numbersColumn"/>
<?php foreach ($files as $f) echo "<col id=\"file{$f}\" class=\"file\"/>"; ?>
            <col id="numbersColumnRight" class="numbersColumn"/>
        </colgroup>
        <thead>
            <tr>
                <td></td>
<?php foreach ($files as $f) echo "<th>{$f}</th>"; ?>
                <td></td>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td></td>
<?php foreach ($files as $f) echo "<th>{$f}</th>"; ?>
                <td></td>
            </tr>
        </tfoot>
        <tbody>
<?php
// actual board starts here

$light = true;  // altering squares color
foreach ($ranks as $r) {
    $light = !$light;
    echo "<tr id=\"rank{$r}\" class=\"rank\">\n"
       . "\t<th>{$r}</th>";
    foreach ($files as $f) {
        $light = !$light;
        echo '<td'
           . ' class="square ' . ($light ? 'light' : 'dark') . '"'
           . ' id="square-' . $f . $r . '"><div>';
        if ($cp = $board->{$f.$r}->chesspiece) {
            echo '<span'
               . ' data-chesspiece="' . $cp->letter() . '"'
               . ' class="chesspiece ' . ($cp->isWhite() ? 'white' : 'black') . '"'
               . ' id="chesspiece-' . $f . $r . '">'
               . $cp->utf8()
               . '</span>';
        }    
        echo '</div></td>';
    }
    echo "<th>{$r}</th>\n"
       . "\t</tr>";
}
?>
        </tbody>
    </table>
</div><!-- #chessboard -->
