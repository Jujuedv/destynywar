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
<table border="1">
	<tr>
		<td>
			Rang&nbsp;
		</td>
		<td>
			Spieler&nbsp;
		</td>
		<td>
			Punkte&nbsp;
		</td>
		<?php
			if($userallianzu->fuerhrung || $userallianzu->gruender){
		?>
		<td>
			Gr&uuml;nder
		</td>
		<td>
			F&uuml;hrung
		</td>
		<td>
			Einladen
		</td>
		<td>
			Forum Typ&nbsp;1
		</td>
		<td>
			Forum Typ&nbsp;2
		</td>
		<td>
			Forum Editieren
		</td>
		<td>
			Shoutboxen verwalten
		</td>
		<td>
			Entlassen
		</td>
		<?php
			}
		?>
	</tr>
	<?php
		$rang = 0;
		//$sql = "SELECT * FROM `allianz_user` WHERE `allianzid` = {$_SESSION['allianz']}";
		$sql = "SELECT AU.`userid` as userid, AU.`gruender` as gruender, AU.`fuerhrung` as fuerhrung, AU.`forum` as forum, AU.`forumtype1` as forumtype1, AU.`forumtype2` as forumtype2, AU.`shoutbox` as shoutbox, AU.`einladen` as einladen, AU.`titel` as titel, US.`points` as punkte FROM `allianz_user` as AU, `user` as US  WHERE AU.`allianzid`='{$_SESSION['allianz']}' and AU.`userid`=US.`id` ORDER BY US.`points` DESC , US.`id` ASC";
		$erg = mysql_query($sql);
		$gruenderzahl = SqlObjQuery("SELECT count(*) as cnt FROM `allianz_user` WHERE gruender=1 and allianzid='{$_SESSION['allianz']}'");
		while($out = mysql_fetch_object($erg)){
			$rang++;
	?>
	<tr>
		<td>
			<?=$rang?>.
		</td>
		<td>
			<?=GetUserLink($out->userid)?>
		</td>
		<td>
			<?=GetUserPoints($out->userid)?>
		</td>
		<?php
			if($userallianzu->fuerhrung || $userallianzu->gruender){
		?>
		<td>
			<?=($out->gruender)?(($userallianzu->gruender && ($gruenderzahl->cnt > 1))?'<a href="changerights.php?gruender&id='.$out->userid.'&no">Ja</a>':'Ja'):(($userallianzu->gruender)?'<a href="changerights.php?gruender&id='.$out->userid.'&yes">Nein</a>':'Nein')?>
		</td>
		<td>
			<?=($out->fuerhrung)?((!$out->gruender || $userallianzu->gruender)?'<a href="changerights.php?fuerhrung&id='.$out->userid.'&no">Ja</a>':'Ja'):((!$out->gruender || $userallianzu->gruender)?'<a href="changerights.php?fuerhrung&id='.$out->userid.'&yes">Nein</a>':'Nein')?>
		</td>
		<td>
			<?=($out->einladen)?((!$out->gruender || $userallianzu->gruender)?'<a href="changerights.php?einladen&id='.$out->userid.'&no">Ja</a>':'Ja'):((!$out->gruender || $userallianzu->gruender)?'<a href="changerights.php?einladen&id='.$out->userid.'&yes">Nein</a>':'Nein')?>
		</td>
		<td>
			<?=($out->forumtype1)?((!$out->gruender || $userallianzu->gruender)?'<a href="changerights.php?forumtype1&id='.$out->userid.'&no">Ja</a>':'Ja'):((!$out->gruender || $userallianzu->gruender)?'<a href="changerights.php?forumtype1&id='.$out->userid.'&yes">Nein</a>':'Nein')?>
		</td>
		<td>
			<?=($out->forumtype2)?((!$out->gruender || $userallianzu->gruender)?'<a href="changerights.php?forumtype2&id='.$out->userid.'&no">Ja</a>':'Ja'):((!$out->gruender || $userallianzu->gruender)?'<a href="changerights.php?forumtype2&id='.$out->userid.'&yes">Nein</a>':'Nein')?>
		</td>
		<td>
			<?=($out->forum)?((!$out->gruender || $userallianzu->gruender)?'<a href="changerights.php?forum&id='.$out->userid.'&no">Ja</a>':'Ja'):((!$out->gruender || $userallianzu->gruender)?'<a href="changerights.php?forum&id='.$out->userid.'&yes">Nein</a>':'Nein')?>
		</td>
		<td>
			<?=($out->shoutbox)?((!$out->gruender || $userallianzu->gruender)?'<a href="changerights.php?shoutbox&id='.$out->userid.'&no">Ja</a>':'Ja'):((!$out->gruender || $userallianzu->gruender)?'<a href="changerights.php?shoutbox&id='.$out->userid.'&yes">Nein</a>':'Nein')?>
		</td>
		<td>
			<?=((!$out->gruender || ($userallianzu->gruender && $gruenderzahl->cnt > 1))?'<a onclick="if(confirm(\'Willst du diesen User wirklich aus der Allianz schmeissen?\') == false) return false;" href="allianzen_kick.php?id='.$out->userid.'&">entlassen</a>':'entlassen nicht m&ouml;glich')?>
		</td>
		<?php
			}
		?>
	</tr>
	<?php
		}
	?>
</table>
<?php
}DrawFooter(); ?>