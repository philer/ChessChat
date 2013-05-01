<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

$logfile="../chatLog/chatLog-somehexval.html";

/*
$fp = fopen($logfile, 'a');
fwrite($fp,$_POST["msg"]);
fclose($fp);
*/

$msgTime = $_POST["msgTime"];
$playerName = $_POST["playerName"];
$msg = $_POST["msg"];

require_once("../templates/chatMessage.tpl.php");
