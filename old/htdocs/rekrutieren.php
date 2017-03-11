<?php 
	include_once("data/funcs.php");
	include_once("data/units.php"); 
	DrawHeader("Rekrutieren"); 
	if($buildings["raumhafen"]["Stufe"]){
?>
<?php
$unitname = $_GET['unit'];
$unitdata = $units[$unitname];
$nums = (int)(is_numeric($_POST['num']) && $_POST['num'] >= 0)?$_POST['num']:1;
$unumsreal = SqlArrQuery("SELECT * FROM `planets` WHERE id = {$_SESSION['planetid']}");
if(empty($_GET['unit']) || !isset($unit[$_GET['unit']])){
echo 'FEHLER';
}
else{
	if(
		(round($unitdata["Platin"]*$nums)<=$ress["Platin"]) &&
		(round($unitdata["Energie"]*$nums)<=$ress["Energie"]) &&
		(round($unitdata["Stahl"]*$nums)<=$ress["Stahl"]) &&
		(round($unitdata["Plasma"]*$nums)<=$ress["Plasma"]) &&
		(round($unitdata["Plasmid"]*$nums)<=$ress["Plasmid"]) &&
		(round($unitdata["Nahrung"]*$nums)<=$ress["Nahrung"]) &&
		($unitname == "president" || ($nums <= (($buildings["versorgtePlaetze"][$buildings["bauer"]["Stufe"]]-$unumsreal['troopsGes'])/ $unitdata['Bevoelkerung']))) &&
		($nums >= 1)
		){
		if(!SqlObjQuery("SELECT * FROM `event_recruit` WHERE planet='{$_SESSION['planetid']}'")){
			$ucounts = SqlObjQuery("SELECT * FROM `planets` WHERE id='{$_SESSION['planetid']}'");
			if(($_GET['unit'] == 'president') && ($ucounts->president != 0) && ($_POST['num'] != 1)){
				echo "Man kann nur einen Presidenten pro Planet bauen!";
			}
			else{
				$sql = "INSERT INTO `event_recruit` (`end_unit` , `planet` , `type`  , `unitsleft` , `timePerUnit`) VALUES ('".(time()+$unitdata['Time']/($buildings["raumhafen"]["Stufe"]/$buildings["raumhafen"]["maxlevel"]+1)/$settings['recruitspeed'])."', '{$_SESSION['planetid']}', '{$unitname}', '".($nums-1)."', '".($unitdata['Time']/($buildings["raumhafen"]["Stufe"]/$buildings["raumhafen"]["maxlevel"]+1)/$settings['recruitspeed'])."');";
				//echo $sql;
				if(!mysql_query($sql)) die($sql);
				$sql = "UPDATE planets SET 
				plasma = ({$ressReal["Plasma"]}-'".(round($unitdata["Plasma"]*$nums)*1000)."'), 
				platin = ({$ressReal["Platin"]}-'".(round($unitdata["Platin"]*$nums)*1000)."'), 
				stahl = ({$ressReal["Stahl"]}-'".(round($unitdata["Stahl"]*$nums)*1000)."'), 
				plasmid = ({$ressReal["Plasmid"]}-'".(round($unitdata["Plasmid"]*$nums)*1000)."'), 
				nahrung = ({$ressReal["Nahrung"]}-'".(round($unitdata["Nahrung"]*$nums)*1000)."'), 
				energie = ({$ressReal["Energie"]}-'".(round($unitdata["Energie"]*$nums)*1000)."'),
				troopsGes = (troopsGes + ".($nums*$unitdata['Bevoelkerung']).")
				WHERE id='{$_SESSION['planetid']}'";
				if(!mysql_query($sql)) die($sql);
				mysql_query("DELETE FROM event_recruit WHERE unitsleft <= -1");
			}
		}
		else echo "Es wird schon rekrutiert!";
	}
	else echo "Rohstoffe fehlen";
}
?>
<script type="text/javascript">location.href = "raumhafen.php";</script>

<?php  
	}
	else {
	?><b>Milit&auml;rst&uuml;tzpunkt ist nicht gebaut!</b><br/><br/><h2><a href="<?=$_SERVER["HTTP_REFERER"]?$_SERVER["HTTP_REFERER"]:(".")?>">zur&uuml;ck</a></h2><?php
	}
	DrawFooter(); ?>