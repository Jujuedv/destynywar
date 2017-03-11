<?php include_once("data/funcs.php"); DrawHeader("Allianz - Forum"); ?>
<?php if($_SESSION['allianz'] == NULL){ ?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php }else{ 
DrawAllianzTable();
?>
<br/>
<?php
if(is_numeric($_GET['forum']) && SqlObjQuery("SELECT * FROM `allianzen_foren` WHERE `allianzid` = '{$_SESSION['allianz']}' and (`forumtype1`= false or `forumtype1`= ".(($_SESSION['allianz_data']['forumtype1'])?"true":"false").") and (`forumtype2`= false or `forumtype2`= ".(($_SESSION['allianz_data']['forumtype2'])?"true":"false").") and id='{$_GET['forum']}'")){
	if(!empty($_POST['text']) && !empty($_POST['name'])){
		mysql_query("INSERT INTO `allianzen_themen` ( `forumid` , `creatoruserid` , `name` ) VALUES ({$_GET['forum']}, {$_SESSION['userid']}, '".htmlentities($_POST['name'],ENT_QUOTES)."');");
		$out = SqlObjQuery("SELECT `id` FROM `allianzen_themen` WHERE (`forumid` = {$_GET['forum']}) and (`creatoruserid` = {$_SESSION['userid']}) and (`name` = '".htmlentities($_POST['name'],ENT_QUOTES)."');");
		$thema = $out->id;
		mysql_query("INSERT INTO `allianzen_themen_gelesen` ( `themaid` , `userid` ) VALUES ( {$thema}, {$_SESSION['userid']} );");
		mysql_query("INSERT INTO `allianzen_beitraege` ( `userid` , `inhalt` , `inhalt_original` , `themaid` , `uhrzeit` )
			VALUES (
			'{$_SESSION['userid']}', '".BBcodes(nl2br(htmlentities($_POST['text'],ENT_QUOTES)))."', '".nl2br(htmlentities($_POST['text'],ENT_QUOTES))."', '{$thema}', '".date('d.m.y \\u\\m H:i:s')."'
			);");
			?>
<script type="text/javascript">window.location.href = "allianz_forum.php?forum=<?=$_GET['forum']?>&thema=<?=$thema?>";</script>
<?php
	}else{
?>
<form action="allianz_forum_neues_thema.php?forum=<?=$_GET['forum']?>" method="POST" id="new_holo" name="new_holo">
	<table>
		<tr>
			<td>Themaname: <td><input type="text" name="name" id="name" value="<?=$_POST['name']?>"></td>
		</tr>
		<tr>
			<td colspan="2"><?php BBCodesLeiste("new_holo","text"); ?></td>
		</tr>
		<tr>
			<td>Erster Beitrag: </td><td><textarea name="text" id="text" rows="10" colspan="50"><?=$_POST['text']?></textarea></td>
		</tr>
	</table>
	<input type="Submit" value="Erstellen"/>
</form>
<?php
	}
?>
<?php
}
else{
?>
<form action="allianz_forum_neues_thema.php?forum=<?=$_GET['forum']?>" method="POST" id="new_holo" name="new_holo">
	<table>
		<tr>
			<td>Themaname: <td><input type="text" name="name" id="name" value="<?=$_POST['name']?>"></td>
		</tr>
		<tr>
			<td colspan="2"><?php BBCodesLeiste("new_holo","text"); ?></td>
		</tr>
		<tr>
			<td>Erster Beitrag: </td><td><textarea name="text" id="text" rows="10" colspan="50"><?=$_POST['text']?></textarea></td>
		</tr>
	</table>
	<input type="Submit" id="SEND" name="SEND" value="Erstellen"/>
</form>
<?php
	}
}DrawFooter(); ?>