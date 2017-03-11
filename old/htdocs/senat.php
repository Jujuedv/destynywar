<?php 
	include_once("data/funcs.php");
	DrawHeader("Interplanetarischer Senat"); 
?>
<!--<table><tr><td>Platin </td><td>Energie </td><td>Stahl </td><td>Plasma </td><td>Plasmid </td><td>Nahrung </td></tr><tr><td><?=$ress["Platin"]?></td><td><?=$ress["Energie"]?></td><td><?=$ress["Stahl"]?></td><td><?=$ress["Plasma"]?></td><td><?=$ress["Plasmid"]?></td><td><?=$ress["Nahrung"]?></td></tr></table>/!-->
<h1>Ausbauen</h1>
</center>

<div style="width:1000" id="description">
	<marquee scrollamount="2" scrolldelay="1"><b  style="background-color:#005;">
			Über ein Geb&auml;ude fahren um dessen Beschreibung zu sehen.
	</b></marquee>
</div><center>
<?php
$nobuild = 0;
$buildmin = array();
$lastend = 0;
$erg = mysql_query("SELECT * FROM `event_build` WHERE village='{$_SESSION['planetid']}' ORDER BY end ");
while($out = mysql_fetch_object($erg)){
	echo $buildings[$out->building]["Name"]." auf Stufe ".($out->stufe).date(' \\a\\m d.m.y \\u\\m H:i:s',$out->end)."&nbsp;&nbsp;&nbsp;Restzeit: <b id='restzeit{$nobuild}' > Berechne...</b><script type=\"text/javascript\">";
	if(!$nobuild)	echo "window.setTimeout(\"rekursivrestzeit('restzeit{$nobuild}','".($out->end - $lastend)."')\", 500);";
	else			echo "window.setTimeout(\"SetTimeDur('restzeit{$nobuild}','".($out->end - $lastend)."')\", 100);";
	echo "</script><br/>";
	//echo date(' \\a\\m d.m.y \\u\\m H:i:s',$out->end).":::".date(' \\a\\m d.m.y \\u\\m H:i:s',time()).":::".date(' \\a\\m d.m.y \\u\\m H:i:s');
	$lastend = $out->end;
	$nobuild++;
	$buildings[$out->building]["Stufe"]++;
	$buildmin[$out->building]++;
}
mysql_free_result($erg);
?></center>
<table>
<?php
foreach($builds as $build => $cont){
if($cont["Stufe"] < $cont["maxlevel"]){
?>
	<tr title="<?=htmlentities($cont["Description"],ENT_QUOTES)?>" >
		<td>
			<a href="<?=$build?>.php" onmouseover="document.getElementById('description').innerHTML = '<marquee scrollamount=\'2\' scrolldelay=\'1\'><b  style=\'background-color:#005;\'><?=htmlentities($cont["Description"],ENT_QUOTES)?></b></marquee>'"><?=$cont["Name"]?></a>(Stufe: <?=$cont["Stufe"]-$buildmin[$cont['intname']]?>)
		</td>
		<td>
			<table><tr><td>Platin</td><td>Energie</td><td>Stahl</td><td>Plasma</td><td>Plasmid</td><td>Nahrung</td></tr><tr><td><?=round($cont["Platin"]*pow($cont["Faktor"]["Build"],$cont["Stufe"]))?></td><td><?=round($cont["Energie"]*pow($cont["Faktor"]["Build"],$cont["Stufe"]))?></td><td><?=round($cont["Stahl"]*pow($cont["Faktor"]["Build"],$cont["Stufe"]))?></td><td><?=round($cont["Plasma"]*pow($cont["Faktor"]["Build"],$cont["Stufe"]))?></td><td><?=round($cont["Plasmid"]*pow($cont["Faktor"]["Build"],$cont["Stufe"]))?></td><td><?=round($cont["Nahrung"]*pow($cont["Faktor"]["Build"],$cont["Stufe"]))?></td></tr></table>
		</td>
		<td>
			<?=time_duration(round($cont["Dauer"]*pow($cont["Faktor"]["Time"],$cont["Stufe"])*60/($buildings["senat"]["Stufe"]/$buildings["senat"]["maxlevel"]+1)/$settings['buildspeed']),"h:m:s")?>
		</td>
		<td>
			<?php
			if((round($cont["Platin"]*pow($cont["Faktor"]["Build"],$cont["Stufe"]))<=$ress["Platin"]) &&
		(round($cont["Energie"]*pow($cont["Faktor"]["Build"],$cont["Stufe"]))<=$ress["Energie"]) &&
		(round($cont["Stahl"]*pow($cont["Faktor"]["Build"],$cont["Stufe"]))<=$ress["Stahl"]) &&
		(round($cont["Plasma"]*pow($cont["Faktor"]["Build"],$cont["Stufe"]))<=$ress["Plasma"]) &&
		(round($cont["Plasmid"]*pow($cont["Faktor"]["Build"],$cont["Stufe"]))<=$ress["Plasmid"]) &&
		(round($cont["Nahrung"]*pow($cont["Faktor"]["Build"],$cont["Stufe"]))<=$ress["Nahrung"])){
				if($nobuild > 4){
					?>
					es werden schon 5 Geb&auml;ude gebaut
					<?php
				}
				else{
					?>
					<a href="ausbauen.php?building=<?=$cont['intname']?>">ausbauen</a>
					<?php
				}
			}else{
			?>
			zu wenig Rohstoffe
			<?php
			}
			?>
		</td>
	</tr>
<?php }}?>
</table><br/>

<center>
<h1><?=htmlentities($unit['president']['Name'])?></h1>
<?php $cont = $unit['president']?>
<table><tr>
<td><b>Kosten:&nbsp;&nbsp;&nbsp;</b></td><td><table><tr><td>Platin</td><td>Energie</td><td>Stahl</td><td>Plasma</td><td>Plasmid</td><td>Nahrung</td></tr><tr><td><?=$cont["Platin"]?></td><td><?=$cont["Energie"]?></td><td><?=$cont["Stahl"]?></td><td><?=$cont["Plasma"]?></td><td><?=$cont["Plasmid"]?></td><td><?=$cont["Nahrung"]?></td></tr></table>
</td></tr>		
<tr>
<td><b>Status:&nbsp;&nbsp;&nbsp;</b></td><td><?php
$count = SqlObjQuery("SELECT president FROM `planets` WHERE id='{$_SESSION['planetid']}'");
if($count->president){
	echo "Der President ist vorhanden.";
}
elseif(SqlObjQuery("SELECT * FROM `event_recruit` WHERE planet='{$_SESSION['planetid']}'")){
	echo "Es wird schon rekrutiert.";
}
else{
	$unitdata = $unit['president'];
	$nums = 1;
	if(!((round($unitdata["Platin"]*$nums)<=$ress["Platin"]) &&
		(round($unitdata["Energie"]*$nums)<=$ress["Energie"]) &&
		(round($unitdata["Stahl"]*$nums)<=$ress["Stahl"]) &&
		(round($unitdata["Plasma"]*$nums)<=$ress["Plasma"]) &&
		(round($unitdata["Plasmid"]*$nums)<=$ress["Plasmid"]) &&
		(round($unitdata["Nahrung"]*$nums)<=$ress["Nahrung"]))){
		echo 'Nicht gen&uuml;gend Rohstoffe vorhanden.';
	}
	else echo '<a href="rekrutieren.php?unit=president&num=1">erstellen</a>';
}
$unitdata['Time']/($buildings["raumhafen"]["Stufe"]/$buildings["raumhafen"]["maxlevel"]+1)
?></td></tr></table>
<?php DrawFooter(); ?>