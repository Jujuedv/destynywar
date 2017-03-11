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
		?>(neu)<?php } ?><a href='allianz_forum.php?forum=<?=$out->id?>' style='background-color:#443;'><?=$out->text?></a><br/></td><td>&nbsp;</td>
<?php
}
?>
	</tr>
	<tr>
		<td colspan="16"><hr/></td>
	</tr>
	<tr> 
<?php
mysql_free_result($erg);

if(is_numeric($_GET['forum']) && SqlObjQuery("SELECT * FROM `allianzen_foren` WHERE `allianzid` = '{$_SESSION['allianz']}' and (`forumtype1`= false or `forumtype1`= ".(($_SESSION['allianz_data']['forumtype1'])?"true":"false").") and (`forumtype2`= false or `forumtype2`= ".(($_SESSION['allianz_data']['forumtype2'])?"true":"false").") and id='{$_GET['forum']}'")){
	$sql_query = "SELECT * FROM `allianzen_themen` WHERE `forumid`='{$_GET['forum']}' ORDER BY `name`";
	$erg = mysql_query($sql_query);
	?>
		<td valign="top" colspan="4">
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
	if(is_numeric($_GET['thema']) && SqlObjQuery("SELECT * FROM `allianzen_themen` WHERE `id` ={$_GET['thema']} AND `forumid` ={$_GET['forum']}")){
		if(isset($_GET['del'])){
			mysql_query("UPDATE `allianzen_themen` SET `forumid` = '-1' WHERE `allianzen_themen`.`id` ={$_GET['thema']}");
		?>
<script type="text/javascript">
	window.location.href = window.location.href;
</script>
		<?php
		}
		if(!SqlObjQuery("SELECT * FROM `allianzen_themen_gelesen` WHERE `themaid` = {$_GET['thema']} AND `userid` ={$_SESSION['userid']}")){
			mysql_query("INSERT INTO `allianzen_themen_gelesen` ( `themaid` , `userid` ) VALUES ( {$_GET['thema']}, {$_SESSION['userid']} );");
		}
		mysql_free_result($erg);
		$sql_query = "SELECT * FROM `allianzen_beitraege` WHERE `themaid`='{$_GET['thema']}' ORDER BY `id`";
		$erg = mysql_query($sql_query);
?>
		
		<td valign="top" colspan="12">
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
			<b><a href="allianzen_forum_answer.php?forum=<?=$_GET['forum']?>&thema=<?=$_GET['thema']?>#answer">Antworten</a></b>
		</td>
<?php
	}
	mysql_free_result($erg);
}
/*echo $out."\n<br/><br/>";
foreach($out as $forums){
echo $forums."\n<br/>";
//echo $forums['text']."\n<br/>";
}*/


?>
</tr></table>
<?php
}DrawFooter(); ?>