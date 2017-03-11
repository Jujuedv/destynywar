<?php 
	include_once("data/funcs.php");
	DrawHeader("{$buildings["markt"]["Name"]}");
	if($buildings["markt"]["Stufe"]){ 
?>
<h1><?=$buildings["markt"]["Name"]?></h1>
<br/>
<div style="width:500px"><?=htmlentities($buildings["markt"]["Description"],ENT_QUOTES)?></div>
<br/><br/>
<b>Unter einem Planeten kannst du jetzt Rohstoffe mit <?=$buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]?>% Tribut verschicken.</b>
<?php   
	}
	else {
	?><b>Dieses Gebäude ist nicht gebaut!</b><br/><br/><h2><a href="<?=$_SERVER["HTTP_REFERER"]?$_SERVER["HTTP_REFERER"]:(".")?>">zur&uuml;ck</a></h2><?php
	}
	DrawFooter(); ?>