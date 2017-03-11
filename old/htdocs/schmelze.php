<?php
	include_once("data/funcs.php");
	DrawHeader("{$buildings["schmelze"]["Name"]}"); 
	if($buildings["schmelze"]["Stufe"]){
		$out = SqlObjQuery("SELECT * FROM `planets` WHERE `id` = {$_SESSION['planetid']};");
?>
<h1><?=$buildings["schmelze"]["Name"]?></h1>
<br/>
<div style="width:500px"><?=htmlentities($buildings["schmelze"]["Description"], ENT_QUOTES)?></div>
<br/>
<br/>
<table>
<tr><td><b>Zurzeitige Produktion(Stufe <?=$buildings["schmelze"]["Stufe"]?>):</b></td><td><b><?=$buildings["produktion"][$buildings["schmelze"]["Stufe"]]*$settings['speed']*$out->moral?></b></td></tr>
<tr><td><b>Produktion der nächsten Stufe(<?=$buildings["schmelze"]["Stufe"]+1?>):</b></td><td><b><?=$buildings["produktion"][$buildings["schmelze"]["Stufe"]+1]*$settings['speed']*$out->moral?></b></td></tr>
</table>
<?php  
	}
	else {
	?><b>Dieses Gebäude ist nicht gebaut!</b><br/><br/><h2><a href="<?=$_SERVER["HTTP_REFERER"]?$_SERVER["HTTP_REFERER"]:(".")?>">zur&uuml;ck</a></h2><?php
	}
	DrawFooter(); ?>