				<section id="chatLogFrame"<?php 
if ($this->var['game']->isPlayer()) echo 'class="canMsg"';
				?>">
					<div id="chatLog">
<?php
foreach ($this->var['chatMsgs'] as $this->var['msg']) {
	$this->show('_chatMessage');
}
?>
						<p>
							<span class="msgTime">21:15</span>
							<span class="msgAuthor">Debug</span>
							<span class="msgText" style="white-space:pre;font-family:mono;color:grey"><?php

// echo "\nsession\n";
// print_r($_SESSION);
// print_r(Core::getUser());

echo "\ngame\n";
print_r($this->var['game']);

						?></span>
						</p>
				</div><!-- #chatLog -->
				</section><!-- #chatLogFrame -->

<?php if ($this->var['game']->isPlayer()) { ?>
				<form id="chatForm">
					<fieldset>
						<div>
							<input 	type="text"
									id="chatText"
									autofocus="autofocus"
									autocomplete="off"
								/>
						</div>
						<button	type="submit" id="chatSubmit" class="button">
							<?php echo $this->lang('chat.send') ?>
						</button>
					</fieldset>
				</form>
<?php } ?>
