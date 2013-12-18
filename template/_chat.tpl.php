                <section id="chatLogFrame"<?php 
if ($this->var['game']->isPlayer()) echo ' class="canMsg"';
                ?>>
                    <div id="chatLog">
<?php
foreach ($this->var['chatMsgs'] as $this->var['msg']) {
    $this->show('_chatMessage');
}
?>

<pre><?php
    // var_dump($this->game->board->inCheck(true));
    // var_dump($this->game->board->inCheckmate(true));
    // var_dump($this->game->board->inCheck(false));
    // var_dump($this->game->board->inCheckmate(false));
    // print_r($this->game->board->blockable(new Square('e5'), false));
    // var_dump(Pawn::blockable( $this->game->board, new Square('F4'), true));
    // var_dump(Pawn::blockable( $this->game->board, new Square('F3'), true));
    // var_dump(Pawn::blockable( $this->game->board, new Square('F4'), false));
    // var_dump(Pawn::blockable( $this->game->board, new Square('F5'), false));
    // var_dump($this->game->board->getKing(true)->canMove($this->game->board));
    // var_dump($this->game->board->getKing(false)->canMove($this->game->board));
    // $ranges = $this->game->board->getAttackPaths(new Square('F4'), true);
    // foreach ($ranges as $range) {
    //     if ($range instanceof Range) echo $range . "\n";
    //     else print_r($range);
    // }
    // var_dump(Range::intersect($ranges));
    // echo new Range(
    //         new Square('a',5),
    //         new Square('d',2),
    //         $this->game->board,
    //         true
    //     ) . "\n";
    // echo new Range(
    //         new Square('a',5),
    //         new Square('d',2),
    //         $this->game->board,
    //         false
    //     ). "\n";
    // var_dump($this->game->board->inCheck(true));
    // var_dump($this->game->board->inCheck(false));
    // echo $this->game->board;
    // var_dump($this->game->getStatus());
    // var_dump($this->game->isOver());
    // print_r($this->game->board->range(new Square('a1'), new Square('5a')));
    // echo "======================";
    // print_r($this->game->board->range(new Square('a2'), new Square('2d')));
    // echo "======================";
    // print_r($this->game->board->range(new Square('a5'), new Square('2a')));
    // echo "======================";
    // print_r($this->game->board->range(new Square('a5'), new Square('5A')));
    // echo "======================";
    // print_r($this->game->board->range(new Square('a5'), new Square('6B')));
    // echo "======================";
    // print_r($this->game->board->range(new Square('a5'), new Square('7c')));
    // echo "======================";
    // print_r($this->game->board->range(new Square('d5'), new Square('7b')));
    // echo "======================";
    // print_r($this->game->board->range(new Square('h6'), new Square('c1')));
    // print_r($this->game->board->range(new Square('f1'), new Square('c4')));
?></pre>
                </div><!-- #chatLog -->
                </section><!-- #chatLogFrame -->

<?php if ($this->var['game']->isPlayer()) { ?>
                <form id="chatForm">
                    <fieldset>
                        <div>
                            <input type="text"
                                   id="chatText"
                                   autofocus="autofocus"
                                   autocomplete="off"
                                />
                        </div>
                        <button type="submit" id="chatSubmit" class="button">
                            <?php echo $this->lang('chat.send') ?>
                        </button>
                    </fieldset>
                </form>
<?php } ?>
