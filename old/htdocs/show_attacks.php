<?php 
	include_once("data/funcs.php");
	DrawHeader("Angriffe");
?>

<?php
$out = SqlObjQuery("SELECT count(id) as cnt FROM `event_troops_attack` WHERE `from_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']}) ORDER BY `ankunft`");
if($out->cnt){
?>
Laufende Angriffe:<br/><br/>
<table border="1">
<tr><th>Herkunft</th><th>Ziel</th><th>Ankunft</th><th>Ankunft in</th><?php
foreach($units as $unit => $unitdata)
{
?>
<th><?=htmlentities($unitdata['Name'])?></th>
<?php
}
?>
</tr>
<?php
	$erg = mysql_query("SELECT * FROM `event_troops_attack` WHERE `from_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']}) ORDER BY `ankunft`");
	$count = 0;
	while($out = mysql_fetch_array($erg)){
		$count++;
?>
<tr><td><?=GetPlanetLink($out['from_planet'])?></td><td><?=GetPlanetLink($out['to_planet'])?></td><td><?=date('d.m.y\\<\\b\\r\\/\\>H:i:s',$out['ankunft'])?></td><td><b id='restzeit<?=$count?>' > Berechne...</b><script type="text/javascript">window.setTimeout("rekursivrestzeit('restzeit<?=$count?>',<?=$out['ankunft']?>)", 500);</script></td>
<?php
foreach($units as $unit => $unitdata)
{
?>
<td><?=$out[$unit]?></td>
<?php
}
?>
</tr>
<?php
	};
	mysql_free_result($erg);
?>
</table>
<hr/>
<?php
}



$out = SqlObjQuery("SELECT count(id) as cnt FROM `event_troops_defense` WHERE `from_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']}) ORDER BY `ankunft`");
if($out->cnt){
?>
Laufende Truppengeschenke:<br/><br/>
<table border="1">
<tr><th>Herkunft</th><th>Ziel</th><th>Ankunft</th><th>Ankunft in</th></tr>
<?php
	$erg = mysql_query("SELECT * FROM `event_troops_defense` WHERE `from_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']}) ORDER BY `ankunft`");
	while($out = mysql_fetch_array($erg)){
		$count++;
?>
<tr><td><?=GetPlanetLink($out['from_planet'])?></td><td><?=GetPlanetLink($out['to_planet'])?></td><td><?=date('d.m.y\\<\\b\\r\\/\\>H:i:s',$out['ankunft'])?></td><td><b id='restzeit<?=$count?>' > Berechne...</b><script type="text/javascript">window.setTimeout("rekursivrestzeit('restzeit<?=$count?>',<?=$out['ankunft']?>)", 500);</script></td></tr>
<?php
	}
?>
</table>
<hr/>
<?php
}



$out = SqlObjQuery("SELECT count(id) as cnt FROM `event_troops_attack` WHERE `to_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']}) ORDER BY `ankunft`");
if($out->cnt){
?>
Eintreffende Angriffe:<br/><br/>
<table border="1">
<tr><th>Herkunft</th><th>Ziel</th><th>Ankunft</th><th>Ankunft in</th></tr>
<?php
	$erg = mysql_query("SELECT * FROM `event_troops_attack` WHERE `to_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']}) ORDER BY `ankunft`");
	while($out = mysql_fetch_array($erg)){
		$count++;
?>
<tr><td><?=GetPlanetLink($out['from_planet'])?></td><td><?=GetPlanetLink($out['to_planet'])?></td><td><?=date('d.m.y\\<\\b\\r\\/\\>H:i:s',$out['ankunft'])?></td><td><b id='restzeit<?=$count?>' > Berechne...</b><script type="text/javascript">window.setTimeout("rekursivrestzeit('restzeit<?=$count?>',<?=$out['ankunft']?>)", 500);</script></td></tr>
<?php
	}
?>
</table>
<hr/>
<?php
}


$out = SqlObjQuery("SELECT count(id) as cnt FROM `event_troops_defense` WHERE `to_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']}) ORDER BY `ankunft`");
if($out->cnt){
?>
Eintreffende Truppengeschenke:<br/><br/>
<table border="1">
<tr><th>Herkunft</th><th>Ziel</th><th>Ankunft</th><th>Ankunft in</th></tr>
<?php
	$erg = mysql_query("SELECT * FROM `event_troops_defense` WHERE `to_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']}) ORDER BY `ankunft`");
	while($out = mysql_fetch_array($erg)){
		$count++;
?>
<tr><td><?=GetPlanetLink($out['from_planet'])?></td><td><?=GetPlanetLink($out['to_planet'])?></td><td><?=date('d.m.y\\<\\b\\r\\/\\>H:i:s',$out['ankunft'])?></td><td><b id='restzeit<?=$count?>' > Berechne...</b><script type="text/javascript">window.setTimeout("rekursivrestzeit('restzeit<?=$count?>',<?=$out['ankunft']?>)", 500);</script></td></tr>
<?php
	}
?>
</table>
<hr/>
<?php
}

$out = SqlObjQuery("SELECT count(id) as cnt FROM `event_troops_back` WHERE `to_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']}) ORDER BY `ankunft`");
if($out->cnt){
?>
R&uuml;ckkehrende Angriffe:<br/><br/>

<table border="1">
<tr><th>Herkunft</th><th>Ziel</th><th>Ankunft</th><th>Ankunft in</th><?php
foreach($units as $unit => $unitdata)
{
	if($unit == 'president') continue;
?>
<th><?=htmlentities($unitdata['Name'])?></th>
<?php
}
?>
<td>Platin</td>
<td>Plasma</td>
<td>Energie</td>
<td>Plasmid</td>
<td>Stahl</td>
<td>Nahrung</td>
</tr>
<?php
	$erg = mysql_query("SELECT * FROM `event_troops_back` WHERE `to_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']}) ORDER BY `ankunft`");
	while($out = mysql_fetch_array($erg)){
		$count++;
?>
<tr><td><?=GetPlanetLink($out['from_planet'])?></td><td><?=GetPlanetLink($out['to_planet'])?></td><td><?=date('d.m.y\\<\\b\\r\\/\\>H:i:s',$out['ankunft'])?></td><td><b id='restzeit<?=$count?>' > Berechne...</b><script type="text/javascript">window.setTimeout("rekursivrestzeit('restzeit<?=$count?>',<?=$out['ankunft']?>)", 500);</script></td>
<?php
foreach($units as $unit => $unitdata)
{
	if($unit == 'president') continue;
?>
<td><?=$out[$unit]?></td>
<?php
}
?>

<td><?=$out['Platin']?></td>
<td><?=$out['Plasma']?></td>
<td><?=$out['Energie']?></td>
<td><?=$out['Plasmid']?></td>
<td><?=$out['Stahl']?></td>
<td><?=$out['Nahrung']?></td>
</tr>
<?php
	};
	mysql_free_result($erg);
?>
</table>
<?php
}
?>
<?php DrawFooter(); ?>