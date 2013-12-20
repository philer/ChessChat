<?php

/**
 * Fatal Exceptions pervent a regular page building
 * and send a predefined error template with detailed information instead.
 * The given error message is written to the servers error log.
 * Extend this class for more specific exception handling!
 * @author Philipp Miller
 */
class FatalException extends Exception {
    
    /**
     * title to be displayed for the error message
     * @var     string
     */
    protected $title = 'Fatal Exception';
    
    /**
     * Creates a new FatalException.
     * Use this when overriding for setting the $message
     * @param     string     $message
     */
    public function __construct($message = '') {
        if(!empty($message)) {
            $this->message = $message;
            error_log('ChessChat ' . get_class($this) . ': ' . $message, 0);
        }
        else $this->message = 'Something went very horribly wrong.';
    }
    
    /**
     * Display the error.
     * Template is included here for maximum reliability
     * (e.g. file system problems preventing opening other files)
     */
    public function show() {
        ?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <style media="screen" type="text/css">/*<![CDATA[*/
            body#fatalExceptionBody {
                text-align: center;
                background-color: #222;
                min-width: 800px;
            }
            /* layout */
            #fatalException {
                border: 3px solid #191919;
                background-color: #333;
                max-width: 90%;
                display: block;
                margin: 50px auto;
                color: #eee;
                font-family: 'Courier New', mono, sans;
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
                /*white-space: pre-wrap;*/
            }
            /* stacktrace syntax */
            #stacktrace .line { color: #f88; }
            #stacktrace .class { color: #79f;}
            #stacktrace .function { color: #cf8; }
            #stacktrace .params { color: #fca; }
        /*]]>*/</style>
        <title><?php echo $this->title; ?></title>
    </head>
    <body id="fatalExceptionBody">
        <section id="fatalException">
            <h1 id="exceptionTitle"><?php echo $this->title; ?></h1>
            <div id="exceptionContent">
                <div id="exceptionMessage"><?php echo $this->message; ?></div>
                <h2>Stracktrace:</h2>
                <div id="stacktrace">
                    <ol>
                        <?php
                        
$stacktrace = $this->getTrace();//debug_backtrace();
foreach ($stacktrace as $i) {
    if (!(isset($i['function']) && $i['function'] === 'errorHandler')) {
        
        if (isset($i['file']))
        echo "\n<li><span class=\"file\">{$i['file']}</span>"
           . ":<span class=\"line\">{$i['line']}</span> ";
        
        if (isset($i['class']))
            echo "<span class=\"class\">{$i['class']}{$i['type']}</span>";
        
        if (isset($i['function'])) {
            echo "<span class=\"function\">{$i['function']}(<span class=\"params\">"
               . self::censorArgs($i)
               . "</span>)</span>";
        }
        echo "</li>";
    }
}

                        ?>
                    </ol>
                </div>
            </div><!-- #exceptionContent -->
        </section><!-- #fatalException -->
    </body>
</html>
        <?php
    }
    
    
    /**
     * Don't show passwords in stacktrace!
     * @param     array<string>     a line from stacktrace
     * @return     string             processed function parameters
     */
    public static function censorArgs($i) {
        if (isset($i['args'])) {
            if (isset($i['class']) && (
                   ($i['class'] === 'Database' && $i['function'] === '__construct')
                || ($i['class'] === 'mysqli'   && $i['function'] === 'mysqli')
                )) {
                return ' ... '; // censor database password
            } else {
                $params = ' ';
                foreach($i['args'] as $k) {
                    if (is_object($k)) {
                        $params .= '{' . get_class($k) . ' object}';
                    } elseif (is_array($k)) {
                        $params .= serialize($k);
                    } else {
                        $params .= $k;
                    }
                    $params .= ' ';
                }
                return $params;
            }    
        }
    }
}
