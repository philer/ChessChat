				<section id="chatLogFrame">
					<div id="chatLog">
						<p>
							<span class="msgTime">21:15</span>
							<span class="msgAuthor">Debug</span>
							<span class="msgText" style="white-space:pre;font-family:mono;color:grey"><?php

echo "\nsession\n";
print_r($_SESSION);
print_r(Core::getUser());

echo "\ngame\n";
print_r($this->var['game']);

echo "\nsafeEquals\n";
var_dump(Util::safeEquals('test', 'test')); // true
var_dump(Util::safeEquals('test', 'testt')); // false
var_dump(Util::safeEquals('test', 'asdfasdfasdfadsfadf')); // false
var_dump(Util::safeEquals('testt', 'test')); // false
var_dump(Util::safeEquals('asdfasdfasdfadsfadf', 'test')); // false

						?></span>
						</p>
					</div><!-- #chatLog -->
				</section><!-- #chatLogFrame -->
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
