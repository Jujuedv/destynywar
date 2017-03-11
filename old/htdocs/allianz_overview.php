<?php include_once("data/funcs.php"); DrawHeader("Allianz - Übersicht"); ?>
<?php if($_SESSION['allianz'] == NULL){ ?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php }else{ 
DrawAllianzTable();
?>
<?php
$allianzdata = SqlObjQuery("SELECT * FROM `allianzen` WHERE id='{$_SESSION['allianz']}'");
//$userdata = SqlObjQuery("SELECT * FROM `user` WHERE id='{$planetdata->userid}'");
$userallianzu = SqlObjQuery("SELECT * FROM `allianz_user` WHERE userid='{$_SESSION['userid']}'");
//$userallianz = SqlObjQuery("SELECT * FROM `allianzen` WHERE id='{$userallianzu->allianzid}'");
?>
<br/>
</center>
<table>
	<tr>
		<td valign="top" style="width:350px;">
		</td>
		<td valign="top" style="width:650px;">
		<?php
			$gruenderzahl = SqlObjQuery("SELECT count(*) as cnt FROM `allianz_user` WHERE gruender=1 and allianzid='{$_SESSION['allianz']}'");
			if(!$userallianzu->gruender || ($userallianzu->gruender && $gruenderzahl->cnt > 1)){
		?>
		
			<a href="allianz_quit.php" onclick="if(confirm('Willst du deine Allianz wirklich verlassen?') == false) return false;">Allianz verlassen</a><br/>
		<?php
				if($userallianzu->gruender){
		?>
			<a href="allianz_destroy.php" onclick="if(confirm('Willst du deine Allianz wirklich aufl&ouml;sen?') == false) return false;">Allianz aufl&ouml;sen</a><br/>
		<?php
				}
			}
			else{
		?>
			<a href="allianz_destroy.php" onclick="if(confirm('Willst du deine Allianz wirklich aufl&ouml;sen?') == false) return false;">Allianz aufl&ouml;sen</a><br/>
		<?php
			}
		?>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td valign="top" style="width:350px;">
			<table>
				<b>Mitglieder:</b><br/>
				<tr><td><center><b>Rang</b>:</center></td><td><center><b>User</b>:</center></td><td><center><b>Titel</b>:</center></td><td><center><b>Punkte</b>:</center></td></tr>
				<?php
					$place = 0;
					$sql_query = "SELECT AU.`userid` as uid, AU.`titel` as tit, US.`points` as punkte FROM `allianz_user` as AU, `user` as US  WHERE AU.`allianzid`='{$_SESSION['allianz']}' and AU.`userid`=US.`id` ORDER BY US.`points` DESC , US.`id` ASC";
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
<?php
}DrawFooter(); ?>