<?php
require_once "global.php";

$single=false;

function printpost($data){
	global $single;
	?><h2 class=clear><a href="/blog/<?php
	echo htmlspecialchars($data["shorturl"]);
	?>"><?php
	echo htmlspecialchars($data["title"]);
	?></a></h2><p><em><?php
		echo htmlspecialchars(date("l d/m/Y g:i A",strtotime($data["posted"])));
	?></em><?php
	if(!$single){
		?> &bull; <a href="/blog/<?php
		echo htmlspecialchars($data["shorturl"]);
		?>">View Comments</a><?php
	}
	?></p><article class=clear><?php
	echo Markdown($single||!stristr($data["post"],"<!--more-->")?$data["post"]:substr($data["post"],0,strpos($data["post"],"<!--more-->")));
	if(!$single){
		?><p><strong><a href="/blog/<?php
		echo htmlspecialchars($data["shorturl"]);
		?>">Read More</a></strong></p><?php
	}
	?></article><?php
	if($single){
		?><div id=disqus_thread></div><script><?php
			?>var disqus_shortname="<?php
			echo htmlspecialchars(urlencode(BLOGDOWN_DISQUS_NAME));
			?>",disqus_title="<?php
			echo htmlspecialchars($data["title"]);
			?>",disqus_url=disqus_identifier="<?php
			echo (empty($_SERVER["HTTPS"])?"http":"https")."://".$_SERVER["HTTP_HOST"];
			?>/blog/<?php
			echo htmlspecialchars(urlencode($data["shorturl"]));
		?>"</script><script src="//<?php
		echo htmlspecialchars(urlencode(BLOGDOWN_DISQUS_NAME));
		?>.disqus.com/embed.js" async></script><noscript>Please enable JavaScript to view the <a href="//disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript><?php
	}
}

$list=mysqli_query($link,"SELECT * FROM posts".(isset($_SERVER["PATH_INFO"])&&!empty($_SERVER["PATH_INFO"])?" WHERE shorturl='".esc(substr($_SERVER["PATH_INFO"],1))."'":"")." ORDER BY id DESC".($all?"":" LIMIT 10"));
if(!$list||mysqli_num_rows($list)==0){
	require_once "404.php";
	die();
}

require_once "includes/markdown.php";

if(mysqli_num_rows($list)==1){
	$single=true;
	$row=mysqli_fetch_array($list);
	blogdown_header("Blog &raquo; ".htmlspecialchars($row["title"]),htmlspecialchars($row["shorturl"]));
	printpost($row);
}else{
	blogdown_header("Blog","_home");
	while($row=mysqli_fetch_array($list))printpost($row);
}
blogdown_footer();