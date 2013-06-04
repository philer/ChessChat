				<section>
					<div id="chatLog">
						<p class="msgOwn">
							<span class="msgTime">17:13</span>
							<span class="msgAuthor">Phil</span>
							<span class="msgText">Checkmate :D</span>
						</p>
						<p class="msgOther">
							<span class="msgTime">21:13</span>
							<span class="msgAuthor">Larissa</span>
							<span class="msgText">oh noes!</span>
						</p>
						<p class="msgBot">
							<span class="msgTime">21:14</span>
							<span class="msgAuthor">Phil </span>
							<span class="msgText">(&#x2657;E3) Bishop from C2 to E3</span>
						</p>
						<p class="msgOwn">
							<span class="msgTime">21:15</span>
							<span class="msgAuthor">Phil</span>
							<span class="msgText">More text and more text and even more text and this is so much text it just HAS to break the line</span>
						</p>
						<p class="msgOther">
							<span class="msgTime">02:00</span>
							<span class="msgAuthor">Larissa</span>
							<span class="msgText">That's a lot of text</span>
						</p>
						<p class="msgOther">
							<span class="msgTime">02:56</span>
							<span class="msgAuthor">Larissa</span>
							<span class="msgText">I'll make some more text, too.</span>
						</p>
						<p class="msgBot">
							<span class="msgTime">21:14</span>
							<span class="msgAuthor">Larissa </span>
							<span class="msgText">moved Queen &#x265B; from D8 to D5</span>
						</p>
						<p class="msgOwn">
							<span class="msgTime">21:15</span>
							<span class="msgAuthor">Phil</span>
							<span class="msgText">
obligatory features<br />
- ajax chat<br />
- move via chat msg ("QB4")<br />
- server-side move validation<br />
- user system / url identification
							</span>
						</p>
						<p class="msgOwn">
							<span class="msgTime">21:15</span>
							<span class="msgAuthor">Phil</span>
							<span class="msgText">
optional features<br />
- move via drag & drop (JS)<br />
- client-side validation (JS)<br />
- saving games (MySQL)<br />
- possible moves highlight (JS)<br />
- clock
							</span>
						</p>
						<p class="msgOwn">
							<span class="msgTime">21:15</span>
							<span class="msgAuthor">Phil</span>
							<span class="msgText" style="white-space:pre;">remaining $route from PATH_INFO
<?php var_dump(Core::getRoute()) ?></span>
						</p>
						<p class="msgOwn">
							<span class="msgTime">21:15</span>
							<span class="msgAuthor">Phil</span>
							<span class="msgText">using Controller <?php echo get_class(Core::getController()); ?></span>
						</p>
					</div><!-- #chatLog -->
				</section>
				<form id="chatForm" action="">
					<fieldset>
						<div>
							<input 	type="text"
									name="chatText"
									id="chatText"
									autofocus="autofocus"
									autocomplete="off"
								/>
						</div>
						<button	type="submit" name="submit" id="chatSubmit">
							<?php lang('chat.send') ?>
						</button>
						<!--<input 	type="submit"
								name="submit"
								id="chatSubmit"
								value="<?php lang('chat.send') ?>"
							/>-->
					</fieldset>
				</form>
