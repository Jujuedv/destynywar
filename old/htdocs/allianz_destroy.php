<?php include_once("data/funcs.php"); DrawHeader("Allianz - Auflösen"); ?>
<?php if($_SESSION['allianz'] == NULL){ ?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php }else{ 


$userallianzu = SqlObjQuery("SELECT * FROM `allianz_user` WHERE userid='{$_SESSION['userid']}'");
if($userallianzu->gruender){
	$sql_query = "SELECT `allianzname` FROM `allianzen` WHERE `id` = {$_SESSION['allianz']}";
	
	$erg = mysql_query($sql_query);
	if(!$erg) $allianzname = "Allianz nicht vorhanden!";
	else{
		$out = mysql_fetch_object($erg);
		$allianzname = $out->allianzname;
		mysql_free_result($erg);
	}

	if($allianzname != "Allianz nicht vorhanden!") {
		$sql_query = "SELECT `userid` FROM `allianz_user` WHERE `allianzid` = {$_SESSION['allianz']}";
		$erg = mysql_query($sql_query);
		if(!$erg) die("Falscher Query: ".$sql_query);
		while($out = mysql_fetch_object($erg))
		{
			SendBericht($out->userid, "Deine Allianz wurde aufgelöst!", "Deine Allianz, {$allianzname}, wurde aufgel&ouml;st!");
					
		}
		mysql_free_result($erg);
		$sql_query = "DELETE FROM `allianzen` WHERE `id` = {$_SESSION['allianz']}";
		mysql_query($sql_query);
 
		$sql_query = "DELETE FROM `allianz_user` WHERE `allianzid` = {$_SESSION['allianz']}";
		mysql_query($sql_query);
		
		$sql_query = "UPDATE `allianzen_foren` SET `allianzid` = '-1' WHERE `allianzid` = {$_SESSION['allianz']}";
		mysql_query($sql_query);
	}
}
else{
}
?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php
}DrawFooter(); ?>