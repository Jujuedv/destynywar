<?php include_once("data/funcs.php"); DrawHeader("Allianz - Forum"); ?>
<?php if($_SESSION['allianz'] == NULL || !$_SESSION['allianz_data']['forum']){ ?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php }else{ 
DrawAllianzTable();
?>
<br/>
<?php
if(isset($_GET['new']) && !empty($_POST['name'])){
	mysql_query("INSERT INTO `allianzen_foren` ( `text` , `allianzid` , `forumtype1` , `forumtype2` ) VALUES ( '".htmlentities($_POST['name'],ENT_QUOTES)."', {$_SESSION['allianz']}, ".((empty($_POST['forumtype1']))?"0":"1").", ".((empty($_POST['forumtype2']))?"0":"1")." );");
}
if(is_numeric($_GET['forum']) && SqlObjQuery("SELECT * FROM `allianzen_foren` WHERE `allianzid` = '{$_SESSION['allianz']}' and (`forumtype1`= false or `forumtype1`= ".(($_SESSION['allianz_data']['forumtype1'])?"true":"false").") and (`forumtype2`= false or `forumtype2`= ".(($_SESSION['allianz_data']['forumtype2'])?"true":"false").") and id='{$_GET['forum']}'")){
	if(isset($_GET['del'])){
		mysql_query("UPDATE `allianzen_foren` SET `allianzid` = '-1' WHERE `allianzen_foren`.`id` = {$_GET['forum']}");
	}
}
$sql_query = "SELECT * FROM `allianzen_foren` WHERE `allianzid` = '{$_SESSION['allianz']}' and (`forumtype1`= false or `forumtype1`= ".(($_SESSION['allianz_data']['forumtype1'])?"true":"false").") and (`forumtype2`= false or `forumtype2`= ".(($_SESSION['allianz_data']['forumtype2'])?"true":"false").") ORDER BY `text`";
$erg = mysql_query($sql_query);
?>
<table border="0">
<?php
$colls = 0;
while($out = mysql_fetch_object($erg)){
?>
		<tr><td style='background-color:#443;'><?=$out->text?></td><td><a href='allianz_forum.php?forum=<?=$out->id?>' >ansehen</a><br/></td><td><a href='allianzen_forum_admin.php?del&forum=<?=$out->id?>'>l&ouml;schen</a><br/></td></tr>
<?php
}
?>
<?php
mysql_free_result($erg);



?>
</tr></table>
<br/>
<a href="#answer" id="new_forum_link" onclick="document.getElementById('answer').style.display = '';document.getElementById('new_forum_link').style.display =  'none';">Neues Forum erstellen</a>
<div id="answer" name="answer" style="display:none;">
	<form id="new_forum" name="new_forum" method="POST" action="allianzen_forum_admin.php?new">
		<table>
			<tr><td valign="top">Name:</td><td valign="top"><input type="text" id="name" name="name" size="50" /></td></tr>
			<?php if($_SESSION['allianz_data']['forumtype1']){ ?><tr><td valign="top">Forumrecht 1 ben&ouml;tigt:</td><td valign="top"><input name="forumtype1" type="checkbox" /></td></tr> <?php } ?>
			<?php if($_SESSION['allianz_data']['forumtype2']){ ?><tr><td valign="top">Forumrecht 2 ben&ouml;tigt:</td><td valign="top"><input name="forumtype2" type="checkbox" /></td></tr> <?php } ?>
		</table>
		<input type="submit" name="Send" value="Forum erstellen"/>
	</form>
</div>
<?php
}DrawFooter(); ?>