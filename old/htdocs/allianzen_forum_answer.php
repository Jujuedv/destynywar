<?php include_once("data/funcs.php"); DrawHeader("Allianz - Forum"); ?>
<?php if($_SESSION['allianz'] == NULL){ ?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php }else{ 
DrawAllianzTable();
?>
<br/>
<?php
$sql_query = "SELECT * FROM `allianzen_foren` WHERE `allianzid` = '{$_SESSION['allianz']}' and (`forumtype1`= false or `forumtype1`= ".(($_SESSION['allianz_data']['forumtype1'])?"true":"false").") and (`forumtype2`= false or `forumtype2`= ".(($_SESSION['allianz_data']['forumtype2'])?"true":"false").") ORDER BY `text`";
$erg = mysql_query($sql_query);
?>
<table colspan="1" border="0">
	<tr>
<?php
$colls = 0;
while($out = mysql_fetch_object($erg)){
	if(!($colls%8)){
?>
	</tr>
	<tr>
<?php
	}
	$colls++;
?>
		<td><?php
		$o1 = SqlObjQuery("SELECT count(`id`) as cnt FROM `allianzen_themen_gelesen` WHERE `userid` = {$_SESSION['userid']} and`themaid` IN (SELECT `id` FROM `allianzen_themen` WHERE `forumid` = {$out->id})");
		$o2 = SqlObjQuery("SELECT count(`id`) as cnt FROM `allianzen_themen` WHERE `forumid` = {$out->id}");
		if($o1->cnt < $o2->cnt){
		?>(neu)<?php } ?><a href='allianz_forum.php?forum=<?=$out->id?>' style='background-color:#443;'><?=$out->text?></a><br/></td>
<?php
}
?>
	</tr>
	<tr>
		<td colspan="8"><hr/></td>
	</tr>
	<tr> 
<?php
mysql_free_result($erg);

if(is_numeric($_GET['forum']) && SqlObjQuery("SELECT * FROM `allianzen_foren` WHERE `allianzid` = '{$_SESSION['allianz']}' and (`forumtype1`= false or `forumtype1`= ".(($_SESSION['allianz_data']['forumtype1'])?"true":"false").") and (`forumtype2`= false or `forumtype2`= ".(($_SESSION['allianz_data']['forumtype2'])?"true":"false").") and id='{$_GET['forum']}'")){
	$sql_query = "SELECT * FROM `allianzen_themen` WHERE `forumid`='{$_GET['forum']}' ORDER BY `name`";
	$erg = mysql_query($sql_query);
	?>
		<td valign="top" colspan="2">
			<table colspan="1" border="0">
	<?php
	while($out = mysql_fetch_object($erg)){
	?>
				<tr><td><?php if(!SqlObjQuery("SELECT * FROM `allianzen_themen_gelesen` WHERE `themaid` = {$out->id} AND `userid` ={$_SESSION['userid']}")){?>(neu)<?php } ?><a href='allianz_forum.php?forum=<?=$_GET['forum']?>&thema=<?=$out->id?>' style='background-color:#443;'><?=$out->name?></a></td><td>&nbsp;|</td><?php if($_SESSION['allianz_data']['forum']){?>
				<td><a href='allianz_forum.php?del&forum=<?=$_GET['forum']?>&thema=<?=$out->id?>' onclick="if(confirm('Willst du dieses Thema wirklich l&ouml;schen?') == false){return false;}">l&ouml;schen</a></td><td>&nbsp;|</td><?php } ?></tr>
	<?php
	}
	?>
				<tr><td>&nbsp;</td></tr>
				<tr><td><a href="allianz_forum_neues_thema.php?forum=<?=$_GET['forum']?>">Neues Thema</a></td></tr>
			</table>
		</td>
	
	
	<?php
	if(is_numeric($_GET['thema'])){
		mysql_free_result($erg);
		$sql_query = "SELECT * FROM `allianzen_beitraege` WHERE `themaid`='{$_GET['thema']}' ORDER BY `id`";
		$erg = mysql_query($sql_query);
?>
		
		<td valign="top" colspan="6">
			<table colspan="1" border="0">
<?php
		$counter = 0;
		while($out = mysql_fetch_object($erg)){
			$counter++;
?>
				<tr><td valign="top" style='background-color:#<?=(($counter-1)%2)?'555':'333'?>;'><?=GetUserLink($out->userid)?><br/><?=GetUserPlatz($out->userid)?>. Platz<br/><?=GetUserPoints($out->userid)?> Punkte<br/></td><td  valign="top" style='background-color:#<?=($counter%2)?'555':'333'?>;'><?=$out->inhalt?></td></tr><tr><td style="height:10px"><hr/></td><td style="height:10px"><hr/></td></tr>
<?php
		}
?>
			</table>
			<?php
		if(isset($_POST["Send"]) && !empty($_POST['inhalt'])){
			mysql_query("INSERT INTO `allianzen_beitraege` ( `userid` , `inhalt` , `inhalt_original` , `themaid` , `uhrzeit` )
				VALUES (
				{$_SESSION['userid']}, '".BBcodes(nl2br(htmlentities($_POST['inhalt'],ENT_QUOTES)))."', '".nl2br(htmlentities($_POST['inhalt'],ENT_QUOTES))."', '{$_GET['thema']}', '".date('d.m.y \\u\\m H:i:s')."'
				);");
			mysql_query("DELETE FROM `allianzen_themen_gelesen` WHERE `themaid` = {$_GET['thema']}");
			mysql_query("INSERT INTO `allianzen_themen_gelesen` ( `themaid` , `userid` ) VALUES ( {$_GET['thema']}, {$_SESSION['userid']} );");
?>
		<script type="text/javascript">
			window.location.href = "allianz_forum.php?forum=<?=$_GET['forum']?>&thema=<?=$_GET['thema']?>";
		</script>
<?php
		}
		else{
?>
		<b name="answer" id="answer">Antworten:</b>
		<form id="send_forum" name="send_forum" method="POST" action="allianzen_forum_answer.php?forum=<?=$_GET['forum']?>&thema=<?=$_GET['thema']?>">
			<?php BBCodesLeiste('send_forum','inhalt'); ?>
			<textarea id="inhalt" name="inhalt" rows="10" cols="50"></textarea><br/>
			<input type="submit" name="Send" value="Abschicken"/>
		</form>
<?php
		}
	}
?>
		</td>
<?php
	}
	mysql_free_result($erg);

/*echo $out."\n<br/><br/>";
foreach($out as $forums){
echo $forums."\n<br/>";
//echo $forums['text']."\n<br/>";
}*/


?>
</tr></table>
<?php
}DrawFooter(); ?>		