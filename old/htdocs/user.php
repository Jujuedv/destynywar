<?php
include_once("data/funcs.php");
$id = 0;
if(is_numeric($_GET['id'])){
	$id = $_GET['id'];    
}
$username = getUserName($id);
if($username == ""){
	drawheader("User nicht gefunden !"); 
?>
<h1>User nicht gefunden!</h1> 
<?php
	drawfooter();
	exit();
}
	
$description = "";
$place = getUserPlatz($id);
$points = getUserPoints($id);

$stufe;
if($points <= $settings['stufe322']) 		$stufe = 3;
elseif($points <= $settings['stufe221'])	$stufe = 2;
else						$stufe = 1;

$allianzid = getUserAllianz($id);
$allianzname = getAllianzName($allianzid);
$sql_query = "SELECT `description` FROM `user` WHERE `id` = {$id}";
$erg = mysql_query($sql_query);
 if(!$erg) die("Falscher Query: ".$sql_query);
 while($out = mysql_fetch_object($erg)){
	$description = $out->description; 
 }
 mysql_free_result($erg);
 
 
	drawheader($username);
?>

<h1><?=$username?></h1>
<table>
 <tr>
   <td valign="top" style="width:300px;">
   
	<table>
	 <tr>
	  <td>Rang:</td> <td><a href="rang.php?rang=<?=$place?>"><?=$place?></a></td>	
	 </tr>
	 <tr>
	  <td>Punkte:</td> <td><?=$points?></td>
	 </tr>
	 <tr>
	  <td>Stufe:</td> <td><?=$stufe?></td>
	 </tr>
	 <tr>
	  <td>Allianz:</td> <td>
<?php
	if($allianzname){
?>
	  <a href="allianz_data.php?id=<?=$allianzid?>"><?=$allianzname?></a>
<?php
	}
	else{
?>
	  ---
<?php
	}
?>
	</td>
	 </tr>
	 <?php
		if($_SESSION['allianz'] && $_SESSION['allianz_data']['einladen']){
			$sql = "SELECT * FROM allianzen_einladungen WHERE userid='{$id}' and allianzid='{$_SESSION['allianz']}'";
			$erg = mysql_query($sql);
			if($out = mysql_fetch_object($erg)  || ( getUserAllianz($id) == $_SESSION['allianz'])){
			}
			else {
	 ?>
	 <tr>
	  <td colspan="2"><a href="allianz_einladen.php?Send&user=<?=$username?>">In Allianz einladen</a></td>
	 </tr>
	 <?php
			}
		}
	 ?>
	 <tr>
	  <td colspan="2"><a href="holopost.php?at=<?=$username?>">Holobrief schreiben</a></td>
	 </tr>
	</table>
<?php
	$sql = "SELECT * FROM planets WHERE userid = '{$id}' ORDER BY planetname";
	$erg = mysql_query($sql);
?>
<br/><br/><b>Planeten:</b><br/><br/>
<table>
<tr><td><b>Planetenname</b>:</td><td><b>Koordinaten:</b></td><td><b>Punkte:</b></td></tr>
<?php
	while($out = mysql_fetch_object($erg)){
?>
<tr><td><a href="planet.php?id=<?=$out->id?>"><?=$out->planetname?></a></td><td><a href="karte.php?x=<?=$out->xcoords?>&y=<?=$out->ycoords?>">(<?=$out->xcoords?>|<?=$out->ycoords?>)</a></td><td><?=$out->points?></td></tr>
<?php
	}
	mysql_free_result($erg);
?>
	</table>
	
   </td>
   <td valign="top" style="width:700px;">
    <b>Beschreibung:</b>
	<br/>
	<br/>
	<?=$description?>
   </td>
 </tr>
</table>
<br>
<br>

<?php
	drawfooter();
?>
