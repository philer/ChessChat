<section>
    <header>
        <h1>
            <?php echo new Link('game.list', 'Game'); ?>
        </h1>
    </header>
    
    <h2><?php echo $this->lang('game.list.running'); ?></h2>
    <ul class="gameList dataList"><?php

foreach ($this->games['running'] as $game) {
    include(self::includePath('_gameListItem'));
}
    ?></ul>
    <h2><?php echo $this->lang('game.list.finished'); ?></h2>
    <ul class="gameList dataList"><?php

foreach ($this->games['over'] as $game) {
    include(self::includePath('_gameListItem'));
}
    ?></ul>

</section>
