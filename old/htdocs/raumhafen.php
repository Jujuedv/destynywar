<?php 
	include_once("data/funcs.php");
	DrawHeader("{$buildings["raumhafen"]["Name"]}");
	if($buildings["raumhafen"]["Stufe"]){ 
		$unumsreal = SqlArrQuery("SELECT * FROM `planets` WHERE id = {$_SESSION['planetid']}");
?>
<!--<table border="1px"><tr><td>Platin </td><td>Energie </td><td>Stahl </td><td>Plasma </td><td>Plasmid </td><td>Nahrung </td><td>Freie Bev&ouml;lkerung </td></tr><tr><td><?=$ress["Platin"]?></td><td><?=$ress["Energie"]?></td><td><?=$ress["Stahl"]?></td><td><?=$ress["Plasma"]?></td><td><?=$ress["Plasmid"]?></td><td><?=$ress["Nahrung"]?></td><td><?=($buildings["versorgtePlaetze"][$unumsreal["bauer"]]-$unumsreal['troopsGes'])?></td></tr></table>/!-->

</center>
<div style="width:1000" id="description">
	<marquee scrollamount="2" scrolldelay="1"><b  style="background-color:#009;">
			Über eine Einheit fahren um ihre Beschreibung zu sehen.
	</b></marquee>
</div><center>
<?php
$block = false;
if($out = SqlObjQuery("SELECT * FROM `event_recruit` WHERE planet='{$_SESSION['planetid']}'")){
	echo "<table><tr><td>".($out->unitsleft+1)." ".$unit[$out->type]["Name"].":</td><td>Fertig ".date(' \\a\\m d.m.y \\u\\m H:i:s',$out->end_unit+($out->unitsleft*$out->timePerUnit))."</td><td>Restzeit: <b id='restzeit' > Berechne...</b><script type=\"text/javascript\">window.setTimeout(\"rekursivrestzeit('restzeit','".($out->end_unit+($out->unitsleft*$out->timePerUnit))."')\", 500);</script></td></tr>";
	echo "<tr><td>N&auml;chste Einheit:</td><td>Fertig ".date(' \\a\\m d.m.y \\u\\m H:i:s',$out->end_unit)."</td><td>Restzeit: <b id='restzeitnext' > Berechne...</b><script type=\"text/javascript\">window.setTimeout(\"rekursivrestzeit('restzeitnext','".($out->end_unit)."')\", 500);</script></td></tr></table>";
	$block = true;
}
?></center>
<table>
<?php
$troops = array();
$out = SqlObjQuery("SELECT * FROM planets WHERE id={$_SESSION['planetid']}");
foreach($units as $key => $element_value){
	$troops[$key] = $out->$key;
}
$out2 = SqlObjQuery("SELECT SUM(abfangjaeger) as abfangjaeger, SUM(techniker) as techniker, SUM(segler) as segler, SUM(stosstruppe) as stosstruppe, SUM(drone) as drone, SUM(kreuzer) as kreuzer FROM event_troops_defense WHERE to_planet = {$_SESSION['planetid']}");
foreach($units as $key => $element_value){
	$troops[$key] += $out2->$key;
}
$out2 = SqlObjQuery("SELECT SUM(abfangjaeger) as abfangjaeger, SUM(techniker) as techniker, SUM(segler) as segler, SUM(stosstruppe) as stosstruppe, SUM(drone) as drone, SUM(kreuzer) as kreuzer FROM event_troops_attack WHERE from_planet = {$_SESSION['planetid']}");
foreach($units as $key => $element_value){
	$troops[$key] += $out2->$key;
}
$out2 = SqlObjQuery("SELECT SUM(abfangjaeger) as abfangjaeger, SUM(techniker) as techniker, SUM(segler) as segler, SUM(stosstruppe) as stosstruppe, SUM(drone) as drone, SUM(kreuzer) as kreuzer FROM event_troops_back WHERE to_planet = {$_SESSION['planetid']}");
foreach($units as $key => $element_value){
	$troops[$key] += $out2->$key;
}

foreach($units as $unitname => $cont){
if($unitname == 'president') continue;
$unums = (int)min(	$ress["Platin"]/	(($cont['Platin'])	?$cont['Platin']	:0.00000001),
					$ress["Plasma"]/	(($cont['Plasma'])	?$cont['Plasma']	:0.00000001),
					$ress["Nahrung"]/	(($cont['Nahrung'])	?$cont['Nahrung']	:0.00000001),
					$ress["Energie"]/	(($cont['Energie'])	?$cont['Energie']	:0.00000001),
					$ress["Plasmid"]/	(($cont['Plasmid'])	?$cont['Plasmid']	:0.00000001),
					$ress["Stahl"]/		(($cont['Stahl'])	?$cont['Stahl']		:0.00000001),
					($buildings["versorgtePlaetze"][$buildings["bauer"]["Stufe"]]-$unumsreal['troopsGes'])/ $cont['Bevoelkerung']
					);
?>
	<tr title="<?=htmlentities($cont["Description"],ENT_QUOTES)?>" >
		<td >
			<p onmouseover="document.getElementById('description').innerHTML = '<marquee scrollamount=\'2\' scrolldelay=\'1\'><b  style=\'background-color:#009;\'><?=htmlentities($cont["Description"],ENT_QUOTES)?></b></marquee>'"><?=htmlentities($cont["Name"],ENT_QUOTES)?></p>
		</td>
		<td>
			<table><tr><td>Platin</td><td>Energie</td><td>Stahl</td><td>Plasma</td><td>Plasmid</td><td>Nahrung</td><td>Bev&ouml;lkerung</td></tr><tr><td><?=$cont["Platin"]?></td><td><?=$cont["Energie"]?></td><td><?=$cont["Stahl"]?></td><td><?=$cont["Plasma"]?></td><td><?=$cont["Plasmid"]?></td><td><?=$cont["Nahrung"]?></td><td><?=$cont["Bevoelkerung"]?></td></tr></table>
		</td>
		<td>
			<?=time_duration(round($cont["Time"]/($buildings["raumhafen"]["Stufe"]/$buildings["raumhafen"]["maxlevel"]+1))/$settings['recruitspeed'],"h:m:s")?>
		</td>
		<td>
			<?php
			if(!$block){
			?>
			<form method="post" action="rekrutieren.php?unit=<?=$unitname?>"><input size="11" name="num" id="<?=$unitname?>" value="0">
			<input value="Rekrutieren" name="Send" type="submit"><b>max:</b>&nbsp;<a href="#" onclick="$('<?=$unitname?>').value=($('<?=$unitname?>').value != '<?=$unums?>')?'<?=$unums?>':'0';"><?=$unums?></a></form><?php
			}else echo "Es wird schon rekrutiert.";
			?>&nbsp;Vorhanden:&nbsp;<?=$unumsreal[$unitname]?>&nbsp;(<?=$troops[$unitname]?>)
		</td>
	</tr>
<?php }?>
</table>
<center>
<?php  
	}
	else {
	?><b>Dieses Gebäude ist nicht gebaut!</b><br/><br/><h2><a href="<?=$_SERVER["HTTP_REFERER"]?$_SERVER["HTTP_REFERER"]:(".")?>">zur&uuml;ck</a></h2><?php
	}
	DrawFooter(); ?>