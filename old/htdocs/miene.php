<?php 
	include_once("data/funcs.php");
	DrawHeader("{$buildings["miene"]["Name"]}");
	if($buildings["miene"]["Stufe"]){ 
		$out = SqlObjQuery("SELECT * FROM `planets` WHERE `id` = {$_SESSION['planetid']};");
?>
<h1><?=$buildings["miene"]["Name"]?></h1>
<br/>
<div style="width:500px"><?=htmlentities($buildings["miene"]["Description"],ENT_QUOTES)?></div>
<br/>
<br/>
<table>
<tr><td><b>Zurzeitige F&ouml;rderung(Stufe <?=$buildings["miene"]["Stufe"]?>):</b></td><td><b><?=$buildings["produktion"][$buildings["miene"]["Stufe"]]*$settings['speed']*$out->moral?></b></td></tr>
<tr><td><b>F&ouml;rderung der nächsten Stufe(<?=$buildings["miene"]["Stufe"]+1?>):</b></td><td><b><?=$buildings["produktion"][$buildings["miene"]["Stufe"]+1]*$settings['speed']*$out->moral?></b></td></tr>
</table>
<?php   
	}
	else {
	?><b>Dieses Gebäude ist nicht gebaut!</b><br/><br/><h2><a href="<?=$_SERVER["HTTP_REFERER"]?$_SERVER["HTTP_REFERER"]:(".")?>">zur&uuml;ck</a></h2><?php
	}
	DrawFooter(); ?>