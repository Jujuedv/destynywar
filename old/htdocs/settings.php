<?php
include_once("data/funcs.php");
drawheader("Einstellungen");
?>


<?php
if(isset($_GET['profil_text'])){
	mysql_query("UPDATE `user` SET `orgDescription` = '".htmlentities($_POST['text'],ENT_QUOTES)."',`Description` = '".nl2br(BBcodes(htmlentities($_POST['text'],ENT_QUOTES)))."'  WHERE `id` = {$_SESSION['userid']}");
}
?>
<h4>Profiltext:</h4>
<form action="settings.php?profil_text" method="POST">
<textarea name="text" id="text" rows="10" cols="50"><?php
$out = SqlObjQuery("SELECT `orgDescription` FROM `user` WHERE `id` = {$_SESSION['userid']}");
echo $out->orgDescription;
?></textarea><br/>
<input type="submit" value="&auml;ndern"/>
</form>



<br/><hr/><br/>

<?php
if(isset($_GET['pw_change'])){

	$out = SqlObjQuery("SELECT count(id) as cnt FROM user WHERE (username='".$_SESSION['username']."') and (userpasswd='".md5(htmlentities($_POST['oldpasswd'],ENT_QUOTES))."')");
	if($out->cnt == 1){
		if(!empty($_POST['newpasswd'])){
			if($_POST['newpasswd'] == $_POST['newpasswd2']){
				mysql_query("UPDATE `user` SET `userpasswd` = '".md5(htmlentities($_POST['newpasswd'],ENT_QUOTES))."'  WHERE `id` = {$_SESSION['userid']}");
				echo "Passwort erfolgreich ge&auml;ndert!<br/>";
			}
			else echo "Passwortbest&auml;tigung falsch!<br/>";
		}
		else echo "Kein neues Passwort eingegeben!<br/>";
	}
	else echo "Falsches Passwort angegeben!<br/>";
}
?>
<h4>Passwort &auml;ndern:</h4>
<form action="settings.php?pw_change" method="POST">
<table>
<tr><td>Altes Passwort: </td><td><input type="password" value="" name="oldpasswd" id="oldpasswd" /></td></tr>
<tr><td>Neues Passwort: </td><td><input type="password" value="" name="newpasswd" id="newpasswd" /></td></tr>
<tr><td>Passwortbest&auml;tigung: </td><td><input type="password" value="" name="newpasswd2" id="newpasswd2" /></td></tr>
</table>
<input type="submit" value="&auml;ndern"/>
</form>



<br/><hr/><br/>

<h4>Account l&ouml;schen:</h4>
<form action="settings.php?del_user" method="POST">
Passwort: <input type="password" value="" name="passwd" id="passwd" /><input type="submit" onclick="if(confirm('Willst du deinen Account wirklich l&ouml;schen?') == false) return false;" value="l&ouml;schen"/>
</form>
<?php
if(isset($_GET['del_user'])){
	$out = SqlObjQuery("SELECT count(id) as cnt FROM user WHERE (username='".$_SESSION['username']."') and (userpasswd='".md5(htmlentities($_POST['passwd'],ENT_QUOTES))."')");
	if($out->cnt == 1){
		$uid = $_SESSION['userid'];
		//mysql_query("DELETE FROM `event_build` WHERE `village` IN (SELECT `id` FROM `planets` WHERE `userid` = {$uid})");
		//mysql_query("DELETE FROM `event_recruit` WHERE `planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$uid})");
		mysql_query("UPDATE `planets` SET `userid` = -1, `planetname` = 'Alienplanet' WHERE `userid` = {$uid}");
		mysql_query("DELETE FROM `allianz_user` WHERE `userid` = {$uid}");
		mysql_query("DELETE FROM `user` WHERE `id` = {$uid}");
		?>
<script type="text/javascript">
	window.location.href = ".";
</script>
		<?php
	}
}
?>
<?php drawfooter(); ?>