<?php
	include_once("data/funcs.php");
	DrawHeader("Holopost"); 
?>
<?php
	if(isset($_GET['delread'])){
		mysql_query("UPDATE `holopost_betreffs` SET `del_from` = 1 WHERE `from_id` ={$_SESSION['userid']} AND `read_from` =1");
		mysql_query("UPDATE `holopost_betreffs` SET `del_to` = 1 WHERE `to_id` ={$_SESSION['userid']} AND `read_to` =1");
	}
	if(!isset($_GET['read']) || !is_numeric($_GET['id'])){
		if(isset($_GET['delete']) && is_numeric($_GET['id'])){
			if(!($out = SqlObjQuery("SELECT * FROM `holopost_betreffs` WHERE ((del_from = 0 and from_id = {$_SESSION['userid']}) or (del_to = 0 and to_id = {$_SESSION['userid']})) and id={$_GET['id']};"))){
				echo "Kein Recht auf diese Holonachricht!<br/>";
			}
			else{
				$selfs = ($out->from_id != $_SESSION['userid'])? "to" : "from";
				mysql_query("UPDATE `holopost_betreffs` SET `del_{$selfs}` = '1' WHERE `id` = {$_GET['id']}");
				if($out->from_id == $out->to_id) mysql_query("UPDATE `holopost_betreffs` SET `del_to` = '1', `del_from` = '1' WHERE `id` = {$_GET['id']}");
				echo "Holonachricht wurde gel&ouml;scht!<br/>";
			}
		}
		if(isset($_GET['new']) && !empty($_POST['betreff'])){
			if(GetUserId($_POST['at'])){
				echo "<b>Nachricht versendet!</b><br/><br/>";
				//print_r($_POST);
				mysql_query("INSERT INTO `holopost_betreffs` ( `betreff` , `from_id` , `to_id` , `lastedit` ) VALUES ('".htmlentities($_POST['betreff'],ENT_QUOTES)."', '{$_SESSION['userid']}', '".GetUserId($_POST['at'])."', '".time()."');");
				mysql_query("INSERT INTO `holopost_nachrichten` ( `userid` , `holopostid` , `message` , `uhrzeit` ) VALUES ( '{$_SESSION['userid']}', (SELECT MAX(`id`) FROM `holopost_betreffs` WHERE `from_id` = '{$_SESSION['userid']}'), '".BBCodes(nl2br(htmlentities($_POST['inhalt'],ENT_QUOTES)))."', '".date('d.m.y \\u\\m H:i:s')."');");
			}
			else echo "<b>User existiert nicht!</b><br/><br/>";

		}
?>
<table>
<tr><td><b>Gelesen</b>:&nbsp;</td><td><b>Betreff</b>:&nbsp;&nbsp;&nbsp;</td><td><b>Gespr&auml;chs-<br/>partner</b>:</td><td><b>Gespr&auml;chspartner<br/>gelesen</b>:</td></tr>
<tr><td>&nbsp;</td><td></td><td></td><td></td></tr>
<?php
	$erg = mysql_query("SELECT * FROM `holopost_betreffs` WHERE (del_from = 0 and from_id = {$_SESSION['userid']}) or (del_to = 0 and to_id = {$_SESSION['userid']}) ORDER BY lastedit DESC;");
	if(!$erg) die("Falscher Query: ".$sql_query);
	while($out = mysql_fetch_object($erg)){
		$partnerid = ($out->from_id == $_SESSION['userid'])?($out->to_id):($out->from_id);
		$read = ($out->from_id == $_SESSION['userid'])?($out->read_from):($out->read_to);
		$otherread = ($out->from_id != $_SESSION['userid'])?($out->read_from):($out->read_to);
?>
<tr><td style="text-align:right;"><img src="pics/<?=($read)?"HolomailGelesen.png":"HolomailNeu.png"?>" style="height:20px; text-align:right;" /></td><td><a href="holopost.php?read&id=<?=$out->id?>"><?=$out->betreff?></a></td><td><?=GetUserLink($partnerid)?></td><td><img src="pics/<?=($otherread)?"HolomailGelesen.png":"HolomailNeu.png"?>" style="height:20px;" /></td></tr>
<?php
	}
	mysql_free_result($erg);
?>
</table>
<br/><br/>
<a href="#answer" id="Send_holo" <?=!isset($_GET['at'])?$_GET['at']:'style="display:none;"'?> onclick="document.getElementById('answer').style.display = '';document.getElementById('Send_holo').style.display =  'none';">Holonachricht senden</a>
<div id="answer" name="answer" <?=isset($_GET['at'])?$_GET['at']:'style="display:none;"'?>>
	<form id="new_holo" name="new_holo" method="POST" action="holopost.php?new">
		<table>
			<tr><td valign="top">An:</td><td valign="top"><input type="text" id="at" name="at" size="50" value="<?=$_GET['at']?>" /></td></tr>
			<tr><td valign="top">Betreff:</td><td valign="top"><input type="text" id="betreff" name="betreff" size="50"/></td></tr>
			<tr><td colspan="2"><?php BBCodesLeiste("new_holo","inhalt"); ?></td></tr>
			<tr><td valign="top">Nachricht:</td><td valign="top"><textarea id="inhalt" name="inhalt" rows="10" cols="50"></textarea></td></tr>
		</table>
		<input type="submit" name="Send" value="Abschicken"/>
	</form>
</div>
<br/>
<a href="holopost.php?delread" onclick="if(confirm('Wiklich alle gelesenen Nachrichten l&ouml;schen?') == false){return false;}">Alle gelesenen Nachrichten l&ouml;schen</a>
<?php
	}
	else {
		if(!($out = SqlObjQuery("SELECT * FROM `holopost_betreffs` WHERE ((del_from = 0 and from_id = {$_SESSION['userid']}) or (del_to = 0 and to_id = {$_SESSION['userid']})) and id={$_GET['id']};"))){
			echo "Kein Recht auf diese Holonachricht!<br/>";
		}
		else{
			$others = ($out->from_id == $_SESSION['userid'])? "to" : "from";
			$selfs = ($out->from_id != $_SESSION['userid'])? "to" : "from";
			if(isset($_GET['answer'])){
				if(!mysql_query("UPDATE `holopost_betreffs` SET `del_from` = 0, `del_to` = 0, `read_{$others}` = '0',`lastedit` = '".time()."' WHERE `id` = {$_GET['id']}")) die("UPDATE `holopost_betreffs` SET `del_from` = 0, `del_to` = 0, `read_{$others}` = '0',`lastedit` = '".time()."' WHERE `id` = {$_GET['id']}");
				mysql_query("INSERT INTO `holopost_nachrichten` ( `userid` , `holopostid` , `message` , `uhrzeit` ) VALUES ( '{$_SESSION['userid']}', '{$_GET['id']}', '".BBcodes(nl2br(htmlentities($_POST['inhalt'],ENT_QUOTES)))."', '".date('d.m.y \\u\\m H:i:s')."');");
			}
			$partnerid = ($out->from_id == $_SESSION['userid'])?($out->to_id):($out->from_id);
			mysql_query("UPDATE `holopost_betreffs` SET `read_{$selfs}` = '1' WHERE `id` = {$_GET['id']}");
			if($out->from_id == $out->to_id) mysql_query("UPDATE `holopost_betreffs` SET `read_to` = '1', `read_from` = '1' WHERE `id` = {$_GET['id']}");
?>
<table>
	<tr><td valign="top" style='background-color:#555'>Betreff:</td><td valign="top" style='background-color:#555'><?=$out->betreff?></td></tr>
	<tr><td valign="top" style='background-color:#555'>Gespr&auml;chspartner:&nbsp;&nbsp;&nbsp;</td><td valign="top" style='background-color:#555'><?=GetUserLink($partnerid)?></td></tr>
</table>

<?php		
			
			$sql_query = "SELECT * FROM `holopost_nachrichten` WHERE `holopostid`='{$_GET['id']}' ORDER BY `id`";
			$erg = mysql_query($sql_query);
?>
<br/>
<table colspan="1" border="0">
<?php
			$counter = 0;
			while($out = mysql_fetch_object($erg)){
				$counter++;
?>
	<tr><td valign="top" style='background-color:#<?=($counter%2)?'555':'333'?>;'><?=GetUserLink($out->userid)?><br/><?=GetUserPlatz($out->userid)?>. Platz<br/><?=GetUserPoints($out->userid)?> Punkte<br/></td><td  valign="top" style='background-color:#<?=($counter%2)?'555':'333'?>;'><?=$out->message?></td></tr>
<?php
			}
?>
</table><br/>
<a href="#answer" id="Send_holo" onclick="document.getElementById('answer').style.display = '';document.getElementById('Send_holo').style.display =  'none';">Antworten</a>&nbsp;&nbsp;&nbsp;<a href="holopost.php?delete&id=<?=$_GET['id']?>" id="Send_holo" onclick="if(confirm('Wiklich löschen?') == false){return false;}">löschen</a>&nbsp;&nbsp;&nbsp;
<div id="answer" name="answer" style="display:none;">
	<form id="new_holo" name="BB_JUMP" method="POST" action="holopost.php?read&id=<?=$_GET['id']?>&answer">
		<?php BBCodesLeiste("new_holo","inhalt"); ?>
		<textarea id="inhalt" name="inhalt" rows="10" cols="50"></textarea><br/>
		<center><input type="submit" name="Send" value="Abschicken"/></center>
	</form>
</div>
<?php
			mysql_free_result($erg);
		}
	}
?>
<?php DrawFooter(); ?>