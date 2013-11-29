				<section id="chatLogFrame"<?php 
if ($this->var['game']->isPlayer()) echo 'class="canMsg"';
				?>">
					<div id="chatLog">
<?php
foreach ($this->var['chatMsgs'] as $this->var['msg']) {
	$this->show('_chatMessage');
}
?>
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
