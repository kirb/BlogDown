<?php
session_start();

require_once "markdown.php";

function blogdown_header($title,$name){
	?><!DOCTYPE html>
<html lang=en>
<head>
	<title><?php
	echo (empty($title)?"":"$title &bull; ").htmlspecialchars(BLOGDOWN_NAME);
	?></title>
	<meta charset=UTF-8>
	<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body data-page="<?php
echo empty($name)?"unknown":htmlspecialchars($name);
?>">
	<article><?php
}
function blogdown_footer(){
	?><div style="clear:both"></div>
	</article>
</body>
</html><?php
}

if(file_exists("config.php"))require_once "config.php";
else{
	define("BLOGDOWN_NAME","BlogDown");
	blogdown_header("Config Not Found","_error");
	?><h2>The BlogDown config file was not found.</h2>
<p>Please <a href="https://github.com/kirbylover4000/BlogDown/blob/master/config.php">see the default config file</a> for instructions.</p><?php
	blogdown_footer();
	die();
}

function err(){
	header($_SERVER["HTTP_PROTOCOL"]." 500 Internal Server Error");
	blogdown_header("Server Error","_error");
	?><h2>Oops, a server error occurred.</h2>
<p>Please try again later.</p><?php
	die();
}

$link=mysqli_connect(BLOGDOWN_SERVER,BLOGDOWN_USERNAME,BLOGDOWN_PASSWORD) or err();
mysqli_select_db($link,BLOGDOWN_DATABASE) or err();
mysqli_set_charset($link,BLOGDOWN_CHARSET) or err();

function esc($txt){
	global $link;
	return mysqli_real_escape_string($link,$txt);
}
