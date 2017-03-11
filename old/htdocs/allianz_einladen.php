<?php include_once("data/funcs.php"); DrawHeader("Allianz - Einladen"); ?>
<?php if($_SESSION['allianz'] == NULL){ ?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php }else{ 
DrawAllianzTable();
?>
<?php if($_SESSION['allianz_data']['einladen']){
	if(isset($_GET['del']) && !empty($_GET['uid']) && SqlObjQuery("SELECT * FROM allianzen_einladungen WHERE userid='{$_GET['uid']}' and allianzid='{$_SESSION['allianz']}'")){
		mysql_query("DELETE FROM allianzen_einladungen WHERE userid='{$_GET['uid']}' and allianzid='{$_SESSION['allianz']}'");
	}
	$arr = array_merge($_POST,$_GET);
	if((isset($arr['Send'])) && !empty($arr['user'])){
		if($id = getUserId($arr['user'])){
			
			$sql = "SELECT * FROM allianzen_einladungen WHERE userid='{$id}' and allianzid='{$_SESSION['allianz']}'";
			$erg = mysql_query($sql);
			if($out = mysql_fetch_object($erg) || ( getUserAllianz($id) == $_SESSION['allianz'])){
			}
			else {
				mysql_query("INSERT INTO `allianzen_einladungen` ( `allianzid` , `userid` , `allianzname` )
					VALUES (
					'{$_SESSION['allianz']}', '{$id}', '{$_SESSION['allianz_name']}'
					);");
				SendBericht($id,"Du wurdest in die Allianz {$_SESSION['allianz_name']} eingeladen", "Du wurdest von <a href=\"user.php?id={$_SESSION['userid']}\">".getUserName($_SESSION['userid'])."</a> in die Allianz <a href=\"allianz_data.php?id={$_SESSION['allianz']}\">".getAllianzName($_SESSION['allianz'])."</a> eingeladen.");
			}
			mysql_free_result($erg);
		}
	}
?>
<br/>
<form method="post" action="allianz_einladen.php" name="einladen">
	<input maxlength="20" size="10" name="user">
	<input value="Einladen" name="Send" type="submit">
</form>
<?php
}
?>
<br/>
<br/>
Einladungen:<br/>
<table border="1">
<tr><td><b>User:</b></td><td><b>Platz:</b></td><td><b>Punkte:</b></td><?php if($_SESSION['allianz_data']['einladen']){?>
		<td><b>Zur&uuml;ckziehen</b></td>
	<?php } ?></tr>
<?php 
	$sql = "SELECT * FROM allianzen_einladungen WHERE allianzid='{$_SESSION['allianz']}'";
	$erg = mysql_query($sql);
	while($out = mysql_fetch_object($erg)){
?>
<tr><td><?=GetUserLink($out->userid)?></td><td><?=getUserPlatz($out->userid)?>.</td><td><?=getUserPoints($out->userid)?> P.</td>
	<?php if($_SESSION['allianz_data']['einladen']){?>
		<td><a href="allianz_einladen.php?del&uid=<?=$out->userid?>">zur&uuml;ckziehen</a></td>
	<?php } ?></tr>
<?php } ?>
</table>
<?php
}DrawFooter(); ?>