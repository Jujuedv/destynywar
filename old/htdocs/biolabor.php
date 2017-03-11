<?php
	include_once("data/funcs.php");
	DrawHeader("{$buildings["biolabor"]["Name"]}");
	if($buildings["biolabor"]["Stufe"]){
		$out = SqlObjQuery("SELECT * FROM `planets` WHERE `id` = {$_SESSION['planetid']};");
?>
<h1><?=$buildings["biolabor"]["Name"]?></h1>
<br/>
<div style="width:500px"><?=htmlentities($buildings["biolabor"]["Description"],ENT_QUOTES)?></div>
<br/>
<br/>
<table>
<tr><td><b>Zurzeitige Produktion(Stufe <?=$buildings["biolabor"]["Stufe"]?>):</b></td><td><b><?=$buildings["produktion"][$buildings["biolabor"]["Stufe"]]*$settings['speed']*$out->moral?></b></td></tr>
<tr><td><b>Produktion der nächsten Stufe(<?=$buildings["biolabor"]["Stufe"]+1?>):</b></td><td><b><?=$buildings["produktion"][$buildings["biolabor"]["Stufe"]+1]*$settings['speed']*$out->moral?></b></td></tr>
</table>
<?php  
	}
	else {
	?><b>Dieses Gebäude ist nicht gebaut!</b><br/><br/><h2><a href="<?=$_SERVER["HTTP_REFERER"]?$_SERVER["HTTP_REFERER"]:(".")?>">zur&uuml;ck</a></h2><?php
	}
	DrawFooter(); ?>