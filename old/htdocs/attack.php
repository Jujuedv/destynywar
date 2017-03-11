<?php 
	include_once("data/funcs.php");
	DrawHeader("Angreifen");
?>
<?php
if(!empty($_POST['att'])){				//Angreifen
	
	$sqlunits 		= "";
	$sqlvalues 		= "";
	$sqlupdatevalues 		= "";
	$falsedata 		= false;
	$langsamstes 	= 0;
	if(is_numeric($_GET['planet'])){
		$out = SqlObjQuery("SELECT * FROM planets WHERE id={$_GET['planet']}");
		$userdata = SqlObjQuery("SELECT * FROM `user` WHERE id={$out->userid}");
		$selfdata = SqlObjQuery("SELECT * FROM `user` WHERE id={$_SESSION['userid']}");
	}


	$stufe;
	if($userdata->points <= $settings['stufe322']) 		$stufe = 3;
	elseif($userdata->points <= $settings['stufe221'])	$stufe = 2;
	else								$stufe = 1;
	
	$selfstufe;
	if($selfdata->points <= $settings['stufe322']) 		$selfstufe = 3;
	elseif($selfdata->points <= $settings['stufe221'])	$selfstufe = 2;
	else								$selfstufe = 1;
	
	if(($stufe != $selfstufe) && ($userdata->userpasswd != '')) $falsedata = true;
	foreach($units as $unit => $unitdata)
	{
		if(empty($_POST[$unit])) continue;
		if(is_numeric($_POST[$unit]) && SqlObjQuery("SELECT `{$unit}` FROM `planets` WHERE (`{$unit}` >= {$_POST[$unit]}) and (id = {$_SESSION['planetid']})")){
			$sqlunits 		.=  " `{$unit}` ,";
			$sqlvalues 		.=  " {$_POST[$unit]},";
			$sqlupdatevalues.=	" `{$unit}` = (`{$unit}`-{$_POST[$unit]}),";
			if($_POST[$unit] > 0)	$langsamstes 	= 		max($unitdata['speed'],$langsamstes);
		}
		else $falsedata = true;
	}
	if(!$falsedata && is_numeric($_GET['planet']) && $langsamstes){
		mysql_query("UPDATE `planets` SET ".$sqlupdatevalues." `id` = `id` WHERE id = {$_SESSION['planetid']}");
		$runtime = (int)(GetDistance($_SESSION['planetid'],$_GET['planet'])*$langsamstes*60);
		$sql = "INSERT INTO `event_troops_attack` ( `from_planet` , `to_planet` , ".$sqlunits." `ankunft` , `laufzeit` ) VALUES ( '{$_SESSION['planetid']}', '{$_GET['planet']}',".$sqlvalues." ".(time() + ($runtime/$settings['attackspeed'])).", ".($runtime/$settings['attackspeed'])." );";
		mysql_query($sql);
?>
	Angriff erfolgreich gestartet!<br/>
<script type="text/javascript">
	window.setTimeout("window.location.href='show_attacks.php';",1000);
</script>
<?php
	}
	else{ 
?>
	Angriff konnte nicht gestartet werden!<br/>
<script type="text/javascript">
	window.setTimeout("window.back()",1000);
</script>
<?php
	}
	
}
elseif(!empty($_POST['def'])){			//Verteidigen
	
	$sqlunits 		= "";
	$sqlvalues 		= "";
	$sqlupdatevalues 		= "";
	$falsedata 		= false;
	$langsamstes 	= 0;
	$bevoelkerung	= 0;
	foreach($units as $unit => $unitdata)
	{
		if($unit == 'president') continue;
		if(empty($_POST[$unit]) || ( $_POST[$unit] == 0 ) ) continue;
		if(is_numeric($_POST[$unit]) && SqlObjQuery("SELECT `{$unit}` FROM `planets` WHERE (`{$unit}` >= {$_POST[$unit]}) and (id = {$_SESSION['planetid']})")){
			$sqlunits 		.=  " `{$unit}` ,";
			$sqlvalues 		.=  " {$_POST[$unit]},";
			$sqlupdatevalues.=	" `{$unit}` = (`{$unit}`-{$_POST[$unit]}),";
			$bevoelkerung	+= 	$_POST[$unit]*$unitdata['Bevoelkerung'];
			if($_POST[$unit] > 0)	$langsamstes 	= 		max($unitdata['speed'],$langsamstes);
		}
		else $falsedata = true;
	}
	if(!$falsedata && is_numeric($_GET['planet']) && $langsamstes){
		$top = SqlObjQuery("SELECT * FROM `planets` WHERE `id` = {$_GET['planet']}");
		if($buildings["versorgtePlaetze"][$top->bauer]-$top->troopsGes >= $bevoelkerung){
			mysql_query("UPDATE `planets` SET ".$sqlupdatevalues." `troopsGes` = (`troopsGes`- {$bevoelkerung}) WHERE id = {$_SESSION['planetid']}");
			mysql_query("UPDATE `planets` SET `troopsGes` = (`troopsGes`+ {$bevoelkerung}) WHERE id = {$_GET['planet']}");
			$runtime = (int)(GetDistance($_SESSION['planetid'],$_GET['planet'])*$langsamstes*60);
			$sql = "INSERT INTO `event_troops_defense` ( `from_planet` , `to_planet` , ".$sqlunits." `ankunft` ) VALUES ( '{$_SESSION['planetid']}', '{$_GET['planet']}',".$sqlvalues." ".(time() + ($runtime/$settings['attackspeed']))." );";
			mysql_query($sql);
?>
	Unterst&uuml;tzung erfolgreich gestartet!<br/>
<script type="text/javascript">
	window.setTimeout("window.location.href='show_attacks.php';",1000);
</script>
<?php
		}
		else{
?>
	Der Verb&uuml;ndete hat nicht gen&uuml;gend PLatz auf seinem Planeten!<br/>
<script type="text/javascript">
	window.setTimeout("window.back()",1000);
</script>
<?php
		}
	}
	else{
?>
	Unterst&uuml;tzung konnte nicht gestartet werden!<br/>
<script type="text/javascript">
	window.setTimeout("window.back()",1000);
</script>
<?php 
	}
	
}
?>
<?php DrawFooter(); ?>