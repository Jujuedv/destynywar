<?php
	include_once("data/funcs.php");
	DrawHeader("{$buildings["schildgenerator"]["Name"]}"); 
	if($buildings["schildgenerator"]["Stufe"]){
?>
<h1><?=$buildings["schildgenerator"]["Name"]?></h1>
<br/>
<div style="width:500px"><?=htmlentities($buildings["schildgenerator"]["Description"],ENT_QUOTES)?></div>
<br/>
<br/>
<table>
<tr><td><b>Zurzeitige Standardverteidigung(Stufe <?=$buildings["schildgenerator"]["Stufe"]?>):</b></td><td><b><?=$buildings["schild"][$buildings["schildgenerator"]["Stufe"]]["grundverteidigung"]?></b></td></tr>
<tr><td><b>Zurzeitiger Verteidigungsbonus(Stufe <?=$buildings["schildgenerator"]["Stufe"]?>):</b></td><td><b><?=$buildings["schild"][$buildings["schildgenerator"]["Stufe"]]["bonus"]?></b></td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td><b>Standardverteidigung der nächsten Stufe(<?=$buildings["schildgenerator"]["Stufe"]+1?>):</b></td><td><b><?=$buildings["schild"][$buildings["schildgenerator"]["Stufe"]+1]["grundverteidigung"]?></b></td></tr>
<tr><td><b>Verteidigungsbonus der nächsten Stufe(<?=$buildings["schildgenerator"]["Stufe"]+1?>):</b></td><td><b><?=$buildings["schild"][$buildings["schildgenerator"]["Stufe"]+1]["bonus"]?></b></td></tr>
</table>
<?php 
}
	else{
	?><b>Dieses Gebäude ist nicht gebaut!</b><br/><br/><h2><a href="<?=$_SERVER["HTTP_REFERER"]?$_SERVER["HTTP_REFERER"]:(".")?>">zur&uuml;ck</a></h2><?php
	}
DrawFooter(); ?>