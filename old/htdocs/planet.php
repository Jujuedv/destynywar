<?php 
	include_once("data/funcs.php");
	DrawHeader("Planetdaten");
?>
<?php

if(!is_numeric($_GET['id'])){ $_GET['id'] = $_SESSION['planetid']; }

$planetdata = SqlObjQuery("SELECT * FROM `planets` WHERE id={$_GET['id']}");
$userdata = SqlObjQuery("SELECT * FROM `user` WHERE id={$planetdata->userid}");
$selfdata = SqlObjQuery("SELECT * FROM `user` WHERE id={$_SESSION['userid']}");
$userallianzu = SqlObjQuery("SELECT * FROM `allianz_user` WHERE userid='{$planetdata->userid}'");
$userallianz = SqlObjQuery("SELECT * FROM `allianzen` WHERE id='{$userallianzu->allianzid}'");


	$stufe;
	if($userdata->points <= $settings['stufe322']) 		$stufe = 3;
	elseif($userdata->points <= $settings['stufe221'])	$stufe = 2;
	else								$stufe = 1;
	
	$selfstufe;
	if($selfdata->points <= $settings['stufe322']) 		$selfstufe = 3;
	elseif($selfdata->points <= $settings['stufe221'])	$selfstufe = 2;
	else								$selfstufe = 1;
?>

<?php
if($planetdata->userid == $_SESSION['userid']) {
	if(!empty($_POST['pname']))	mysql_query("UPDATE `planets` SET `planetname` = '".htmlentities($_POST['pname'],ENT_QUOTES)."' WHERE `id` = {$_GET['id']}");
	$planetdata = SqlObjQuery("SELECT * FROM `planets` WHERE id={$_GET['id']}");
?>
	<a href='changeplanetname.php?id=<?=$planetdata->id?>'><h1><?=$planetdata->planetname?></h1></a>
<?php
}else{
?>
	<h1><?=$planetdata->planetname?></h1>
<?php
}
?>
</center>
<?php 
if($userdata->userpasswd != ''){
?>
<table>
	<tr>
		<td>
			Spieler:
		</td>
		<td>
			<a href="player.php?id=<?=$planetdata->userid?>"><?=preg_replace("~ ~", "&nbsp;",$userdata->username)?></a>
		</td>
	</tr>
	<tr>
		<td>
			Allianz:
		</td>
		<td>
			<a href="allianz_data.php?id=<?=$userallianzu->allianzid?>"><?=$userallianz->allianzname?></a>
		</td>
	</tr>
	<tr>
		<td>
			Punkte:
		</td>
		<td>
			<?=$planetdata->points?>
		</td>
	</tr>
</table>
<?php }else echo '<br/>'; ?>
<a href="karte.php?x=<?=$planetdata->xcoords?>&y=<?=$planetdata->ycoords?>">Planet zentrieren</a><br/>
<center>
<?php

	//
if((($userallianzu->allianzid != $_SESSION['allianz']  || empty($_SESSION['allianz']))) && !($userdata->id == $_SESSION['userid'])){
if(($stufe == $selfstufe) || ($userdata->userpasswd == '')){
?>
<h1>Angreifen</h1>
<form method="post" action="attack.php?planet=<?=$_GET['id']?>" name="attackform">
	<table>
<?php
$unums = SqlArrQuery("SELECT * FROM `planets` WHERE id = {$_SESSION['planetid']}");
foreach($units as $unit => $unitdata)
{
if(($unit == 'president') && ($planetdata->points < 1500) && $planetdata->userid != -1 ){
	echo "<tr><td>&nbsp;</td></tr><tr><td colspan=\"3\">Man kann keine Planeten von echten Spielern mit weniger als 1500 Punkten &uuml;bernehmen.</td></tr>";
	continue;
}
?>
		<tr><td><?=htmlentities($unitdata['Name'])?>:</td><td><input size="5" name="<?=$unit?>" id="<?=$unit?>" value="0" /></td><td>&nbsp;&nbsp;max: <a href="#" onclick="$('<?=$unit?>').value=($('<?=$unit?>').value != '<?=$unums[$unit]?>')?'<?=$unums[$unit]?>':'0';"><?=$unums[$unit]?></a></td></tr>
<?php } ?>
	</table>
	<input size="5" name="att" type="submit" value="Angreifen">
	<!--<input size="5" name="def" type="submit" value="Verteidigen">/-->
</form>
<?php
}
else echo "<br/>Dieser Spieler hat entweder zu wenig oder zu viele Punkte, als dass du ihn angreifen könntest.<br/>";
}
else{
	if($userdata->id == $_SESSION['userid']){
		echo "</center><br/><a href=\"planet.php?id={$planetdata->id}&changeplanet={$planetdata->id}\">Zu Planet wechseln</a><br/><center>";
	}
	if($buildings["markt"]["Stufe"]){
	?>
	<h4>Rohstoffe verschicken</h4>
	<form action="send_ress.php?id=<?=$planetdata->id?>" method="POST">
		<table border="1">
			<tr>
			  <td><b>Rohstoff</b></td>
			
			  <td>Platin</td>
			  <td>Plasma</td>
			  <td>Energie</td>
			  <td>Plasmid</td>
			  <td>Stahl</td>
			  <td>Nahrung</td>
			</tr>
			<tr>
			  <td><b>Menge</b></td>
			  <td><input type="text" name="Platin" id="Platin" size="7" value="0"/></td>
			  <td><input type="text" name="Plasma" id="Plasma" size="7" value="0"/></td>
			  <td><input type="text" name="Energie" id="Energie" size="7" value="0"/></td>
			  <td><input type="text" name="Plasmid" id="Plasmid" size="7" value="0"/></td>
			  <td><input type="text" name="Stahl" id="Stahl" size="7" value="0"/></td>
			  <td><input type="text" name="Nahrung" id="Nahrung" size="7" value="0"/></td>
			</tr>
			<tr>
			  <td><b>max.</b></td>
			  <td><a href="#" onclick="$('Platin').value=($('Platin').value != '<?=$ress['Platin']?>')?'<?=$ress['Platin']?>':'0';"><?=$ress['Platin']?></a></td>
			  <td><a href="#" onclick="$('Plasma').value=($('Plasma').value != '<?=$ress['Plasma']?>')?'<?=$ress['Plasma']?>':'0';"><?=$ress['Plasma']?></a></td>
			  <td><a href="#" onclick="$('Energie').value=($('Energie').value != '<?=$ress['Energie']?>')?'<?=$ress['Energie']?>':'0';"><?=$ress['Energie']?></a></td>
			  <td><a href="#" onclick="$('Plasmid').value=($('Plasmid').value != '<?=$ress['Plasmid']?>')?'<?=$ress['Plasmid']?>':'0';"><?=$ress['Plasmid']?></a></td>
			  <td><a href="#" onclick="$('Stahl').value=($('Stahl').value != '<?=$ress['Stahl']?>')?'<?=$ress['Stahl']?>':'0';"><?=$ress['Stahl']?></a></td>
			  <td><a href="#" onclick="$('Nahrung').value=($('Nahrung').value != '<?=$ress['Nahrung']?>')?'<?=$ress['Nahrung']?>':'0';"><?=$ress['Nahrung']?></a></td>
			</tr>
		</table>
		<input type="submit" value="versenden" />
	</form>
	<br/><br/>
	<h4>Truppen verschenken</h4>
	<form method="post" action="attack.php?planet=<?=$_GET['id']?>" name="attackform">
	<table border="1px">
	<tr><td>Freie Pl&auml;tze:</td><td><?=($buildings["versorgtePlaetze"][$planetdata->bauer]-$planetdata->troopsGes)?></td><td></td></tr>
<?php
		$unums = SqlArrQuery("SELECT * FROM `planets` WHERE id = {$_SESSION['planetid']}");
			foreach($units as $unit => $unitdata)
			{
				if($unit == 'president') continue;

?>
		<tr><td><?=$unitdata['Name']?>:</td><td><input size="5" name="<?=$unit?>" id="<?=$unit?>" value="0" /></td><td>&nbsp;&nbsp;max: <a href="#" onclick="$('<?=$unit?>').value=($('<?=$unit?>').value != '<?=$unums[$unit]?>')?'<?=$unums[$unit]?>':'0';"><?=$unums[$unit]?></a></td></tr>
<?php 
			} 
?>
	</table>
	<input size="5" name="def" type="submit" value="Verschenken" onclick="if(confirm('Willst du diese Truppen wirklich verschenken?') == false) return false;" />
	<!--<input size="5" name="def" type="submit" value="Verteidigen">/-->
</form>

<?php

	?>
<?php
	}
}
?>

<?php DrawFooter(); ?>
