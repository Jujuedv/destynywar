<?php 
	include_once("data/funcs.php");
	DrawHeader("Planetdaten");
?>
<?php
if(!is_numeric($_GET['id'])){ DrawFooter(); exit(); }

$planetdata = SqlObjQuery("SELECT * FROM `planets` WHERE id='{$_GET['id']}'");
$userdata = SqlObjQuery("SELECT * FROM `user` WHERE id='{$planetdata->userid}'");
$userallianzu = SqlObjQuery("SELECT * FROM `allianz_user` WHERE userid='{$planetdata->userid}'");
$userallianz = SqlObjQuery("SELECT * FROM `allianzen` WHERE id='{$userallianzu->allianzid}'");

if(
	($buildings["markt"]["Stufe"])				&&
	($ress['Platin'] >= $_POST['Platin']) 		&&
	($ress['Plasma'] >= $_POST['Plasma']) 		&&
	($ress['Plasmid'] >= $_POST['Plasmid']) 	&&
	($ress['Energie'] >= $_POST['Energie']) 	&&
	($ress['Nahrung'] >= $_POST['Nahrung']) 	&&
	($ress['Stahl'] >= $_POST['Stahl'])			&&
	is_numeric($_POST['Platin'])				&&
	is_numeric($_POST['Plasma'])				&&
	is_numeric($_POST['Plasmid'])				&&
	is_numeric($_POST['Energie'])				&&
	is_numeric($_POST['Nahrung'])				&&
	is_numeric($_POST['Stahl'])				
	){
	mysql_query($str = "UPDATE `planets` SET 
	`Platin`= (`Platin` + ".($_POST['Platin']*((100-($buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]))/100)*1000)."), 
	`Plasma`= (`Plasma` + ".($_POST['Plasma']*((100-($buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]))/100)*1000)."), 
	`Plasmid`= (`Plasmid` + ".($_POST['Plasmid']*((100-($buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]))/100)*1000)."), 
	`Energie`= (`Energie` + ".($_POST['Energie']*((100-($buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]))/100)*1000)."), 
	`Nahrung`= (`Nahrung` + ".($_POST['Nahrung']*((100-($buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]))/100)*1000)."), 
	`Stahl`= (`Stahl` + ".($_POST['Stahl']*((100-($buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]))/100)*1000).") 
	WHERE `id`={$_GET['id']}");
	mysql_query($str = "UPDATE `planets` SET 
	`Platin`= (".$ressReal['Platin']." - ".($_POST['Platin']*1000)." ), 
	`Plasma`= (".$ressReal['Plasma']." - ".($_POST['Plasma']*1000)."), 
	`Plasmid`= (".$ressReal['Plasmid']." - ".($_POST['Plasmid']*1000)."), 
	`Energie`= (".$ressReal['Energie']." - ".($_POST['Energie']*1000)."), 
	`Nahrung`= (".$ressReal['Nahrung']." - ".($_POST['Nahrung']*1000)."), 
	`Stahl`= (".$ressReal['Stahl']." - ".($_POST['Stahl']*1000).")
	WHERE `id`={$_SESSION['planetid']}");
	
	SendBericht($userdata->id,"Dir wurden Rohstoffe geschickt.",
	GetUserName($_SESSION['userid'])." hat nach ".GetPlanetLink($_GET['id'])." folgende Rohstoffe geschickt:<br/>"."<table><tr><td>Platin</td><td>Plasma</td><td>Energie</td><td>Plasmid</td><td>Stahl</td><td>Nahrung</td></tr>
    <tr><td>".(int)($_POST['Platin']*((100-($buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]))/100))."</td>
      <td>".(int)($_POST['Plasma']*((100-($buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]))/100))."</td>
      <td>".(int)($_POST['Energie']*((100-($buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]))/100))."</td>
      <td>".(int)($_POST['Plasmid']*((100-($buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]))/100))."</td>
      <td>".(int)($_POST['Stahl']*((100-($buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]))/100))."</td>
      <td>".(int)($_POST['Nahrung']*((100-($buildings["markt"]["maxlevel"]-$buildings["markt"]["Stufe"]))/100))."</td></tr></table>"
);
	$out = SqlObjQuery("SELECT * FROM `planets` WHERE id = '{$_SESSION['planetid']}'");
	
	$ress["Platin"] 									= (((int)($out->platin/1000))>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]])?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]:(int)($out->platin/1000);
	$ress["Energie"] 									= (((int)($out->energie/1000))>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]])?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]:(int)($out->energie/1000);
	$ress["Stahl"] 										= (((int)($out->stahl/1000))>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]])?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]:(int)($out->stahl/1000);
	$ress["Plasma"] 									= (((int)($out->plasma/1000))>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]])?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]:(int)($out->plasma/1000);
	$ress["Plasmid"] 									= (((int)($out->plasmid/1000))>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]])?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]:(int)($out->plasmid/1000);
	$ress["Nahrung"] 									= (((int)($out->nahrung/1000))>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]])?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]:(int)($out->nahrung/1000);
	
	$ressReal["Platin"] 									= (($out->platin)>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000)?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000:($out->platin);
	$ressReal["Energie"] 									= (($out->energie)>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000)?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000:($out->energie);
	$ressReal["Stahl"] 										= (($out->stahl)>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000)?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000:($out->stahl);
	$ressReal["Plasma"] 									= (($out->plasma)>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000)?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000:($out->plasma);
	$ressReal["Plasmid"] 									= (($out->plasmid)>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000)?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000:($out->plasmid);
	$ressReal["Nahrung"] 									= (($out->nahrung)>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000)?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000:($out->nahrung);
	
	
}



?>
<h1><?=$planetdata->planetname?></h1>
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
