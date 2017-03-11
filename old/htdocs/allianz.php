<?php include_once("data/funcs.php"); DrawHeader("Allianz"); ?>
<?php if($_SESSION['allianz'] == NULL){ ?>
<form action="allianz_erstellen.php" method="POST"><input type="text" size="15" name="allianzname" value=""/><br/><br/><input type="submit" name="Send" value="Allianz erstellen"/></form>
<br/><br/>
Einladungen:<br/>
<table border="1">
<?php 
	$sql = "SELECT * FROM allianzen_einladungen WHERE userid='{$_SESSION['userid']}'";
	$erg = mysql_query($sql);
	while($out = mysql_fetch_object($erg)){
?>
<tr><td><a href="allianz_data.php?id=<?=$out->allianzid?>"><?=$out->allianzname?></a></td><td><a href="allianz_einladung_accept.php?id=<?=$out->id?>">annehmen</a></td><td><a href="allianz_einladung_decline.php?id=<?=$out->id?>">ablehnen</a></td></tr>
<?php } ?>
</table>
<?php
}else{ 
DrawAllianzTable();
?>

<?php
}DrawFooter(); ?>