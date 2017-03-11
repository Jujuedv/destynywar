<?php 
	include_once("data/funcs.php");
	DrawHeader("Allianzdaten");
?>
<?php
$allianzdata = SqlObjQuery("SELECT * FROM `allianzen` WHERE id='{$_GET['id']}'");
//$userdata = SqlObjQuery("SELECT * FROM `user` WHERE id='{$planetdata->userid}'");
//$userallianzu = SqlObjQuery("SELECT * FROM `allianz_user` WHERE userid='{$planetdata->userid}'");
//$userallianz = SqlObjQuery("SELECT * FROM `allianzen` WHERE id='{$userallianzu->allianzid}'");
?>
<h1><?=($out = getAllianzName($_GET['id']))?$out:"Allianz existiert nicht!"?></h1>
</center>
<table>
	<tr>
		<td valign="top" style="width:350px;">
			<table>
				<b>Mitglieder:</b><br/>
				<tr><td><center><b>Rang</b>:</center></td><td><center><b>User</b>:</center></td><td><center><b>Titel</b>:</center></td><td><center><b>Punkte</b>:</center></td></tr>
				<?php
					$place = 0;
					$sql_query = "SELECT AU.`userid` as uid, AU.`titel` as tit, US.`points` as punkte FROM `allianz_user` as AU, `user` as US  WHERE AU.`allianzid`='{$_GET['id']}' and AU.`userid`=US.`id` ORDER BY US.`points` DESC , US.`id` ASC";
					$erg = mysql_query($sql_query);
					if(!$erg) die("Falscher Query: ".$sql_query);
					while($out = mysql_fetch_object($erg)){
						$place++;
					?>
						<tr><td><?=$place?>.</td><td><a href="player.php?id=<?=$out->uid?>"><?=GetUserName($out->uid)?></a></td><td><?=empty($out->tit)?"":"(".$out->tit.")"?></td><td><?=$out->punkte?></td></tr>
					<?php
					}
					mysql_free_result($erg);
				?>
			</table>
		</td>
		<td valign="top" style="width:650px;">
			<b>Beschreibung:</b><br/>
			<?=nl2br(BBcodes($allianzdata->allianztext))?>
		</td>
	</tr>
</table>
<center>
<?php DrawFooter(); ?>
