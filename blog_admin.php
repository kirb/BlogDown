<?php
require_once "global.php";

if(isset($_POST["edit"])&&!empty($_POST["edit"])){
	$edited=isset($_POST["save"])&&$_POST["save"]==1;
	$row=$edited?array(
		"title"=>$_POST["title"],
		"post"=>$_POST["post"],
		"shorturl"=>$_POST["shorturl"]
	):array();
	if($edited&&!isset($_POST["preview"])){
		mysqli_query($link,($_POST["edit"]=="new"?"INSERT INTO":"UPDATE")." posts SET title='".esc($_POST["title"])."',post='".esc($_POST["post"])."',shorturl='".esc($_POST["shorturl"])."',updated=NOW()".($_POST["edit"]=="new"?",posted=NOW()":" WHERE id='".esc($_POST["edit"])));
		$_SESSION["blog_admin_saved"]=true;
		header("Location: /blog_admin");
		die();
	}elseif(!$edited&&$_POST["edit"]!="new"){
		$list=mysqli_query($link,"SELECT * FROM posts WHERE id='".esc($_POST["edit"])."'");
		if(!$list||mysqli_num_rows($list)!=1){
			require_once "404.php";
			die();
		}
		$row=mysqli_fetch_array($list);
	}
	blogdown_header("Blog &raquo; Edit","_admin");
	if(isset($_POST["preview"])&&$_POST["preview"]==1){
		?><h2><?php
		echo htmlspecialchars($row["title"]);
		?></h2><div class=clear></div><article><?php
		require_once "markdown.php";
		echo Markdown($row["post"]);
		?></article><?php
	}
	?><form action="" method=post><input name=title placeholder=Title value="<?php
	echo htmlspecialchars($row["title"]);
	?>" style="width:100%;display:block;font-size:20px"><textarea name=post placeholder=Text style="width:100%;height:450px;display:block;resize:vertical"><?php
	echo htmlspecialchars($row["post"]);
	?></textarea><input name=shorturl placeholder=URL value="<?php
	echo htmlspecialchars($row["shorturl"]);
	?>" style="width:100%;display:block"><input type=hidden name=edit value="<?php
	echo htmlspecialchars($_POST["edit"]);
	?>"><input type=hidden name=save value=1><button type=submit style="padding:8px 14px">Save</button> <button name=preview value=1 style="padding:8px 14px">Preview</button></form><?php
	blogdown_footer();
	die();
}


$list=mysqli_query($link,"SELECT * FROM posts ORDER BY id DESC");
if($list&&mysqli_num_rows($list)>0){
	blogdown_header("Blog &raquo; Admin","_admin");
	if(isset($_SESSION["blog_admin_saved"])&&$_SESSION["blog_admin_saved"]){
		unset($_SESSION["blog_admin_saved"]);
		?><div style="background:lime;border:2px dotted green;padding:6px">Saved</div><?php
	}
	?><form action="" method=post><table style="width:100%"><tr><td></td><td><button type=submit name=edit value=new>New</button></td></tr><?php
	while($row=mysqli_fetch_array($list)){
		?><tr><th><?php
		echo htmlspecialchars($row["id"]);
		?>: <?php
		echo htmlspecialchars($row["title"]);
		?></th><td style="width:1px"><button type=submit name=edit value="<?php
		echo htmlspecialchars($row["id"]);
		?>">Edit</button></td></tr><?php
	}
	?></table></form><?php
	blogdown_footer();
}else{
	require_once "404.php";
	die();
}
