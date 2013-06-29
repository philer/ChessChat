				<section id="chatLogFrame">
					<div id="chatLog">
						<p>
							<span class="msgTime">21:15</span>
							<span class="msgAuthor">Debug</span>
							<span class="msgText" style="white-space:pre;font-family:mono;color:grey"><?php

echo "\nsession\n";
var_dump($_SESSION);

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
						<button	type="submit" id="chatSubmit">
							<?php echo $this->lang('chat.send') ?>
						</button>
					</fieldset>
				</form>
