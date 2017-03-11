<?php
	include_once("data/funcs.php");
	DrawHeader("{$buildings["bauer"]["Name"]}"); 
	if($buildings["bauer"]["Stufe"]){
		$out = SqlObjQuery("SELECT * FROM `planets` WHERE `id` = {$_SESSION['planetid']};");
?>
<h1><?=$buildings["bauer"]["Name"]?></h1>
<br/>
<div style="width:500px"><?=htmlentities($buildings["bauer"]["Description"],ENT_QUOTES)?></div>
<br/>
<br/>
<table>
<tr><td><b>Zurzeitige Produktion(Stufe <?=$buildings["bauer"]["Stufe"]?>):</b></td><td><b><?=$buildings["produktion"][$buildings["bauer"]["Stufe"]]*$settings['speed']*$out->moral?></b></td></tr>
<tr><td><b>Zurzeitige Versorgung(Stufe <?=$buildings["bauer"]["Stufe"]?>):</b></td><td><b><?=$buildings["versorgtePlaetze"][$buildings["bauer"]["Stufe"]]*$out->moral?></b></td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td><b>Produktion der nächsten Stufe(<?=$buildings["bauer"]["Stufe"]+1?>):</b></td><td><b><?=$buildings["produktion"][$buildings["bauer"]["Stufe"]+1]*$settings['speed']*$out->moral?></b></td></tr>
<tr><td><b>Versorgung der nächsten Stufe(<?=$buildings["bauer"]["Stufe"]+1?>):</b></td><td><b><?=$buildings["versorgtePlaetze"][$buildings["bauer"]["Stufe"]+1]*$out->moral?></b></td></tr>
</table>
<?php 
	}
	else{
	?><b>Dieses Gebäude ist nicht gebaut!</b><br/><br/><h2><a href="<?=$_SERVER["HTTP_REFERER"]?$_SERVER["HTTP_REFERER"]:(".")?>">zur&uuml;ck</a></h2><?php
	}
	DrawFooter();
?>