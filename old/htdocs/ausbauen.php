<?php 
	include_once("data/funcs.php");
	DrawHeader("Interplanetarischer Senat"); 
?>
<?php
if(empty($_GET['building']) || !isset($buildings[$_GET['building']])){
}
else{
	$erg = mysql_query("SELECT * FROM `event_build` WHERE village='{$_SESSION['planetid']}'");
	while($out = mysql_fetch_object($erg)){
		$buildings[$out->building]["Stufe"]++;
	}
	mysql_free_result($erg);
	if(
		(round($buildings[$_GET['building']]["Platin"]*pow($buildings[$_GET['building']]["Faktor"]["Build"],$buildings[$_GET['building']]["Stufe"]))<=$ress["Platin"]) &&
		(round($buildings[$_GET['building']]["Energie"]*pow($buildings[$_GET['building']]["Faktor"]["Build"],$buildings[$_GET['building']]["Stufe"]))<=$ress["Energie"]) &&
		(round($buildings[$_GET['building']]["Stahl"]*pow($buildings[$_GET['building']]["Faktor"]["Build"],$buildings[$_GET['building']]["Stufe"]))<=$ress["Stahl"]) &&
		(round($buildings[$_GET['building']]["Plasma"]*pow($buildings[$_GET['building']]["Faktor"]["Build"],$buildings[$_GET['building']]["Stufe"]))<=$ress["Plasma"]) &&
		(round($buildings[$_GET['building']]["Plasmid"]*pow($buildings[$_GET['building']]["Faktor"]["Build"],$buildings[$_GET['building']]["Stufe"]))<=$ress["Plasmid"]) &&
		(round($buildings[$_GET['building']]["Nahrung"]*pow($buildings[$_GET['building']]["Faktor"]["Build"],$buildings[$_GET['building']]["Stufe"]))<=$ress["Nahrung"]) &&
		($buildings[$_GET['building']]["Stufe"] < $buildings[$_GET['building']]["maxlevel"])
		){
		$out = SqlObjQuery("SELECT count(*) as cnt, MAX(end) as mend FROM `event_build` WHERE village='{$_SESSION['planetid']}'");
		if($out->cnt < 5){
			$st = time();
			if($out->cnt) $st = $out->mend;
			$sql = "INSERT INTO `event_build` (`end` , `village` , `building` , `stufe` ) VALUES ('".($st+round($buildings[$_GET['building']]["Dauer"]*pow($buildings[$_GET['building']]["Faktor"]["Time"],$buildings[$_GET['building']]["Stufe"])*60/($buildings["senat"]["Stufe"]/$buildings["senat"]["maxlevel"]+1)/$settings['buildspeed']))."', '{$_SESSION['planetid']}', '{$_GET['building']}', '".($buildings[$_GET['building']]["Stufe"]+1)."');";
			if(!mysql_query($sql)) die($sql);
			$sql = "UPDATE planets SET 
			plasma = ({$ressReal["Plasma"]}-'".(round($buildings[$_GET['building']]["Plasma"]*pow($buildings[$_GET['building']]["Faktor"]["Build"],$buildings[$_GET['building']]["Stufe"]))*1000)."'), 
			platin = ({$ressReal["Platin"]}-'".(round($buildings[$_GET['building']]["Platin"]*pow($buildings[$_GET['building']]["Faktor"]["Build"],$buildings[$_GET['building']]["Stufe"]))*1000)."'), 
			stahl = ({$ressReal["Stahl"]}-'".(round($buildings[$_GET['building']]["Stahl"]*pow($buildings[$_GET['building']]["Faktor"]["Build"],$buildings[$_GET['building']]["Stufe"]))*1000)."'), 
			plasmid = ({$ressReal["Plasmid"]}-'".(round($buildings[$_GET['building']]["Plasmid"]*pow($buildings[$_GET['building']]["Faktor"]["Build"],$buildings[$_GET['building']]["Stufe"]))*1000)."'), 
			nahrung = ({$ressReal["Nahrung"]}-'".(round($buildings[$_GET['building']]["Nahrung"]*pow($buildings[$_GET['building']]["Faktor"]["Build"],$buildings[$_GET['building']]["Stufe"]))*1000)."'), 
			energie = ({$ressReal["Energie"]}-'".(round($buildings[$_GET['building']]["Energie"]*pow($buildings[$_GET['building']]["Faktor"]["Build"],$buildings[$_GET['building']]["Stufe"]))*1000)."') 
			WHERE id='{$_SESSION['planetid']}'";
			if(!mysql_query($sql)) die($sql);
		}
	}
}
?>
<script type="text/javascript">
	window.location.href = "senat.php";
</script>
<?php DrawFooter(); ?>