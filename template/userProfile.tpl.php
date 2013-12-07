<section>
    <header>
        <h1>
<?php echo new Link($this->controller->getPageTitle(), $this->controller->getCanonicalRoute()); ?>
        </h1>
    </header>
<?php
if (!$this->user->isSelf()) {
    echo '<a href="'
       . Util::url('Game/new/?opponent=' . urlencode($this->user))
       . '">'
       . $this->lang('game.new.against', array('opponent' => $this->user))
       . '</a>';
}
?>

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
