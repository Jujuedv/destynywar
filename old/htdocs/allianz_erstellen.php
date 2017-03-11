<?php include_once("data/funcs.php"); DrawHeader("Allianz"); ?>
<?php if($_SESSION['allianz'] == NULL){ 
	if($_POST['Send']){
		$ok = true;
		if(empty($_POST['allianzname'])) $ok = false;
		if($ok){
			$allianzname = htmlentities($_POST['allianzname'],ENT_QUOTES);
			$sql = "SELECT * FROM allianzen WHERE allianzname='{$allianzname}'";
			$erg = mysql_query($sql);
			if(($out = mysql_fetch_object($erg)) == false){
				mysql_free_result($erg);
				mysql_query("INSERT INTO allianzen ( allianzname , allianztext ) VALUES ('{$allianzname}' , 'Diese Allianz wurde von [player]{$_SESSION['username']}[/player] gegründet.\nWendet euch bei Fragen an [player]{$_SESSION['username']}[/player]');");
				$erg = mysql_query($sql);
				$out = mysql_fetch_object($erg);
				mysql_free_result($erg);
				
				mysql_query("INSERT INTO `allianz_user` ( `userid` , `titel` , `gruender` , `fuerhrung` , `diplomatie` , `forum` , `forumtype1` , `forumtype2` , `einladen`, `allianzid`, `shoutbox` )
VALUES (
'{$_SESSION['userid']}', '', true, true, true, true, true, true, true, '{$out->id}' , true
);
");
				?><script type="text/javascript">window.location.href = "allianz.php";</script><?php
			}
			else mysql_free_result($erg);
			$error = "Allianzname schon vergeben.";
		}
	}
	?>
	<form action="allianz_erstellen.php" method="POST"><input type="text" size="15" name="allianzname" value=""/><br/><br/><input type="submit" name="Send" value="Allianz erstellen"/></form>
<br/><br/>
Einladungen:<br/>
<table border="1">
<?php 
	$sql = "SELECT * FROM allianzen_einladungen WHERE userid='{$_SESSION['userid']}'";
	$erg = mysql_query($sql);
	while($out = mysql_fetch_object($erg)){
?>
<tr><td><a href="allianz_info.php?id=<?=$out->allianzid?>"><?=$out->allianzname?></a></td><td><a href="allianz_einladung_accept.php?id=<?=$out->id?>">annehmen</a></td><td><a href="allianz_einladung_decline.php?id=<?=$out->id?>">ablehnen</a></td></tr>
<?php } ?>
</table>
<?php }else{ ?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php }DrawFooter(); ?>