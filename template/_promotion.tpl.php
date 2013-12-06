<?php
$white = (boolean) $_POST['white'];
$pieces = array(
    new Queen($white),
    new Knight($white),
    new Rook($white),
    new Bishop($white)
);
?><section id="promotion">
    <header>
        <h1>
            <?php echo $this->lang('chess.promotion'); ?>
        </h1>
    </header>
    <p><?php echo $this->lang('chess.promotion.explanation'); ?></p>
    <ul>
<?php foreach ($pieces as $cp) { ?>
        <li><a
            data-chesspiece="<?php echo $cp->letter(); ?>"
            class="promotion-option chesspiece icon button"><?php
            echo $cp;
            ?></a></li>
<?php } ?>
    </ul>
</section>
