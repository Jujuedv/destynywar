<?php
	include_once("data/funcs.php");
	DrawHeader("Holoberichte"); 
?>
<?php
if(isset($_GET['deleteall'])) mysql_query("DELETE FROM `holoberichte` WHERE `userid` = '{$userid}';");
if(is_numeric($_GET['id'])){
	if($out = SqlObjQuery("SELECT * FROM `holoberichte` WHERE `userid` = '{$userid}' and `id` = '{$_GET['id']}';")){
		if(isset($_GET['delete'])){
			mysql_query("DELETE FROM `holoberichte` WHERE `userid` = '{$userid}' and `id` = '{$_GET['id']}';");
			$sql_query = "SELECT * FROM `holoberichte` WHERE `userid` = '{$userid}' ORDER BY `id` DESC;";
			$erg = mysql_query($sql_query);
			if(!$erg) die("Falscher Query: ".$sql_query);
			?>
			<table><tr><td><b>Betreff:</b></td><td>&nbsp;|&nbsp;</td><td><b>Uhrzeit:</b></td></tr>
			<?php
				while($out = mysql_fetch_object($erg)){
			?><tr><td><?=$out->read?"":"(neu)"?><a href="holoberichte.php?id=<?=$out->id?>"><?=$out->betreff?></a></td><td>&nbsp;|&nbsp;</td><td><?=date('d.m.y \\u\\m H:i:s',$out->time)?></td></tr><?php
				};
				mysql_free_result($erg);
			?>
			</table>
			<?php
		}
		else{
		//Als gelesen markieren
		mysql_query("UPDATE `holoberichte` SET `read`=1 WHERE `userid` = '{$userid}' and `id` = '{$_GET['id']}';");
		
?>
<table><tr><td></td><td><b>Betreff: <?=$out->betreff?></b></td><td>&nbsp;</td><td><b>Uhrzeit: <?=date('d.m.y \\u\\m H:i:s',$out->time)?></b></td><td></td></tr>
<tr>
	<td colspan="3">
		&nbsp;
	</td>
</tr>
<tr>
	<td colspan="3">
		<?=$out->inhalt?>
	</td>
</tr>
</table>
<a href="holoberichte.php?delete&id=<?=$_GET['id']?>" onclick="if(confirm('Wiklich löschen?') == false){return false;}">L&ouml;schen</a>
<?php
		}
	} else echo "Kein Recht auf diesen Holonbericht!!!<br/>";
}else {
	$sql_query = "SELECT * FROM `holoberichte` WHERE `userid` = '{$userid}' ORDER BY `id` DESC;";
	$erg = mysql_query($sql_query);
	if(!$erg) die("Falscher Query: ".$sql_query);
?>
<table><tr><td><b>Betreff:</b></td><td>&nbsp;|&nbsp;</td><td><b>Uhrzeit:</b></td></tr>
<?php
	while($out = mysql_fetch_object($erg)){
?><tr><td><?=$out->read?"":"(neu)"?><a href="holoberichte.php?id=<?=$out->id?>"><?=$out->betreff?></a></td><td>&nbsp;|&nbsp;</td><td><?=date('d.m.y \\u\\m H:i:s',$out->time)?></td></tr><?php
	};
	mysql_free_result($erg);
?>
</table>
<a href="holoberichte.php?deleteall" onclick="if(confirm('Wiklich alle Holoberichte l&ouml;schen?') == false){return false;}">Alle l&ouml;schen</a>
<?php
}
?>

<?php DrawFooter(); ?>


 
 