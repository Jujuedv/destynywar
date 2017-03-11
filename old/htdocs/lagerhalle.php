<?php
	include_once("data/funcs.php");
	DrawHeader("{$buildings["lagerhalle"]["Name"]}"); 
	if($buildings["lagerhalle"]["Stufe"]){
?>
<h1><?=$buildings["lagerhalle"]["Name"]?></h1>
<br/>
<div style="width:500px"><?=htmlentities($buildings["lagerhalle"]["Description"],ENT_QUOTES)?></div>
<br/>
<br/>
<table>
<tr><td><b>Zurzeitige Lagerkapazit&auml;t(Stufe <?=$buildings["lagerhalle"]["Stufe"]?>):</b></td><td><b><?=$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]?></b></td></tr>
<tr><td><b>Lagerkapazit&auml;t der nächsten Stufe(<?=$buildings["lagerhalle"]["Stufe"]+1?>):</b></td><td><b><?=$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]+1]?></b></td></tr>
</table>
<?php  
	}
	else {
	?><b>Dieses Gebäude ist nicht gebaut!</b><br/><br/><h2><a href="<?=$_SERVER["HTTP_REFERER"]?$_SERVER["HTTP_REFERER"]:(".")?>">zur&uuml;ck</a></h2><?php
	}
	DrawFooter(); ?>