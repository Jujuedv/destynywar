<?php
include_once("data/funcs.php");
drawheader("Rangliste");
$type = isset($_GET['allianz'])?1:0;
$rangIN = (is_numeric($_GET['rang']) && ($_GET['rang']>=0))?$_GET['rang']-1:getUserPlatz($_SESSION['userid'])-1;
$step = (is_numeric($_GET['step']) && $_GET['step']>=5 && $_GET['step']<=100)?$_GET['step']:20;

$searchfor = -1;
$urang = 0;
if(!empty($_GET['user']) && ($urang = getUserId($_GET['user']))){
	$rangIN 	= getUserPlatz($urang);
	$searchfor 	= $urang;
}
$realplatz = $rangIN-($rangIN%$step);
$place = $realplatz;
$lastusers = false;
if($type == 0){
	$sql_query = "SELECT * FROM `user` WHERE `userpasswd`<>'' ORDER BY `points` DESC ,`id` ASC LIMIT {$place},{$step} ";
	$erg = mysql_query($sql_query);
	if(!$erg) die("Falscher Query: ".$sql_query);
	?>
	 
	<table border="0" cellpadding="2" cellspacing="2">
			<tr>
			<td><center><b>Rang:</b></center></td>
			<td><center><b>User:</b></center></td>
			<td><center><b>Allianz:</b></center></td>
			<td><center><b>Punkte:</b></center></td>
			</tr>
	 

	<?php
	 while($out = mysql_fetch_object($erg)){
		$uname = preg_replace("~ ~","&nbsp;",$out->username);
		$userally = SqlObjQuery("SELECT * FROM `allianz_user` WHERE userid='{$out->id}'");
		if(!$userally){
			if		($out->id 			== $_SESSION['userid']	) 	$bgcol = "000";
			elseif	($out->id 			== $searchfor			) 	$bgcol = "333";
			else 													$bgcol = "555";
			?>
	<tr>
	<td style="background-color:#<?=$bgcol?>;"><?=$place+1?>.</td>
	<td style="background-color:#<?=$bgcol?>;"><a href="player.php?id=<?=$out->id?>"><?=$uname?></a></td>
	<td style="background-color:#<?=$bgcol?>;">---</td>
	<td style="background-color:#<?=$bgcol?>;"> <?=$out->points?></td>
	</tr>
			<?php
		}
		else{
			$userallydata = SqlObjQuery("SELECT * FROM `allianzen` WHERE id='{$userally->allianzid}'");
			
			if		($out->id 			== $_SESSION['userid']	) 	$bgcol = "000";
			elseif	($out->id 			== $searchfor			) 	$bgcol = "333";
			elseif	($userallydata->id	== $_SESSION['allianz']	) 	$bgcol = "555";
			else 													$bgcol = "444";
			?>  
	<tr>
	<td style="background-color:#<?=$bgcol?>;"><?=$place+1?>.</td>
	<td style="background-color:#<?=$bgcol?>;"><a href="player.php?id=<?=$out->id?>"><?=$uname?></a></td>
	<td style="background-color:#<?=$bgcol?>;"><a href="allianz_data.php?id=<?=$userallydata->id?>"><?=$userallydata->allianzname?></a></td>
	<td style="background-color:#<?=$bgcol?>;"><?=$out->points?></td>
	</tr>
			<?php
		}
		$place++;
	 }
	$lastusers = false;
	 while($place<$realplatz+$step){
		$bgcol 		= "444";
		$lastusers 	= true;
	 ?>
	<tr>
	<td style="background-color:#<?=$bgcol?>;"><?=$place+1?>.</td>
	<td style="background-color:#<?=$bgcol?>;">---</td>
	<td style="background-color:#<?=$bgcol?>;">---</td>
	<td style="background-color:#<?=$bgcol?>;">---</td>
	</tr>
	 <?php
	 $place++;
	 }
	 mysql_free_result($erg);
	 ?>
	</table>
	<a href="rang.php?rang=<?=$realplatz-$step+1?>&step=<?=$step?>" <?=(!$realplatz)?"onclick=\"return false;\" style=\"color:#444;opacity:1;\"":""?>>besser</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="rang.php?rang=<?=$realplatz+$step+1?>&step=<?=$step?>" <?=($lastusers)?"onclick=\"return false;\" style=\"color:#555;opacity:1;\"":""?>>schlechter</a>
	<table><tr><form method="GET" name="useridsearch" id="useridsearch"><td><input type="text" name="rang"/></td><td><input type="submit" name="SEND" value="Rang"/></td></form></tr><tr><form method="GET" name="usersearch" id="usersearch"><td><input type="text" name="user"/></td><td><input type="submit" name="SEND" value="Username"/></td></form></tr></table>
	<?php
	}

?>
<?php drawfooter(); ?>