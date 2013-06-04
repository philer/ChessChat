<?php

/**
 * Fatal Exceptions pervent a regular page building
 * and send a predefined error template with detailed information instead.
 * Extend this class for more specific exception handling!
 * TODO use log-files instead/additionally
 * @author Philipp Miller
 */
class FatalException extends Exception {
	
	/**
	 * title to be displayed for the error message
	 * @var 	string
	 */
	protected $title = "Fatal Exception";
	
	/**
	 * create a new FatalException
	 * use this when overriding for setting the $message
	 * @param 	string 	message to be displayed in the error message
	 */
	public function __construct($message = '') {
		if(!empty($message)) $this->message = $message;
		else $this->message = "Something went very horribly wrong.";
	}
	
	/**
	 * display the error.
	 * template is included here for maximum reliability
	 * (e.g. file system problems preventing opening other files)
	 */
	public function toTpl() {
		?>
<html>
	<head>
		<style media="screen" type="text/css">/*<![CDATA[*/
			body {
				text-align: center;
				background-color: #222;
				color: #eee;
				font-family: 'Courier New', mono, sans;
			}
			/* layout */
			#fatalException {
				border: 3px solid #191919;
				background-color: #333;
				min-width: 700px;
				max-width: 90%;
				display: inline-block;
				margin: 50px auto;
				font-size: 16px;
				overflow: auto;
			}
			h1#exceptionTitle {
				font-size: 1.5em;
				background-color: #2d2d2d;
				margin: 0;
				padding: 0.5em 0;
			}
			h2 {
				margin-bottom: 0.3em;
				font-size: 1.1em;
			}
			#exceptionContent {
				padding: 1em;
				text-align: left;
			}
			#stacktrace {
				font-size: 0.85em;
			}
			/* stacktrace syntax */
			#stacktrace .line { color: #f88; }
			#stacktrace .class { color: #79f;}
			#stacktrace .function { color: #cf8; }
			#stacktrace .params { color: #fca; }
		/*]]>*/</style>
		<title><?php echo $this->title; ?></title>
	</head>
	<body>
		<section id="fatalException">
			<h1 id="exceptionTitle"><?php echo $this->title; ?></h1>
			<div id="exceptionContent">
				<div id="exceptionMessage"><?php echo $this->message; ?></div>
				<h2>Stracktrace:</h2>
				<div id="stacktrace">
					<?php
					//debug_print_backtrace();
					/**/
					echo "<ol>";
					$stacktrace = $this->getTrace();//debug_backtrace();
					foreach($stacktrace as $i) {
						echo "<li><span class=\"file\">".$i['file']."</span>"
							.":<span class=\"line\">".$i['line']."</span> ";
						if (isset($i['class'])) echo "<span class=\"class\">".$i['class'].$i['type']."</span>";
						echo "<span class=\"function\">".$i['function']."( <span class=\"params\">";
						foreach($i['args'] as $k) echo $k." ";
						echo "</span>)</span></li>\n";
					}
					echo "</ol>";
					/**/
					?>
				</div>
			</div><!-- #exceptionContent -->
		</section><!-- #fatalException -->
	</body>
</html>
		<?php
	}
}
