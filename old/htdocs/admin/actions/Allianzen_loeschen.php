<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html template="true">
<head>
  <title>Destyny War - Admin</title>
  <style type="text/css">
body { 
	background-image: url(../../pics/normbg.jpg);
	background-repeat:no-repeat;
	background-attachment:fixed;
	font-family: Courier New;
	background-color: rgb(0, 0, 0);
	color: rgb(255, 0, 0);
}

a:link { color:#FF0000; }
a:visited { color:#FF0000; }
a:active { color:#FF0000; }

.kasten { 
	border: 3px inset rgb(0, 0, 0);
}
.brett { 
	border: 3px outset rgb(0, 0, 0);
}
  </style>
</head>
<body style="background-color: rgb(0, 0, 0); color: rgb(255, 0, 0);">
<?php
	session_start();
	if(!$_SESSION['admin_login']){?>
<script type="text/javascript">
	window.location.href = "..";
</script>
	<?php
	}
	else{
		?>
<table>
	<tr>
		<td valign="top" style="width:350px;">
		<ul>
		<?php
		foreach (glob("*.php") as $filename) {
			$ohnePHP = explode( "." , $filename , -1 );
			echo "<li><a href='$filename'>".$ohnePHP[0]."</a></li>
";
		}
		mysql_connect("localhost", "root","jgames") or die ("Fehler: 1");
		mysql_select_db("dw") or die ("Fehler: 2");
		?>
		</ul>
		</td>
		<td valign="top">
		<center>
		<?php
function SendBericht($userid,$betreff,$nachricht){
 mysql_query("INSERT INTO `holoberichte` ( `gettime` , `userid` , `betreff` , `inhalt`, `time` )
VALUES (
'".date('d.m.y um H:i:s')."', '{$userid}', '".htmlentities($betreff,ENT_QUOTES)."', '".$nachricht."', '".time()."');");
}

if(is_numeric($_GET['id']))
{

	$sql_query = "SELECT `allianzname` FROM `allianzen` WHERE `id` = {$_GET['id']}";
	
	$erg = mysql_query($sql_query);
	if(!$erg) $allianzname = "Allianz nicht vorhanden!";
	else{
		$out = mysql_fetch_object($erg);
		$allianzname = $out->allianzname;
		mysql_free_result($erg);
	}

	if($allianzname != "Allianz nicht vorhanden!")
	{
		$sql_query = "SELECT `userid` FROM `allianz_user` WHERE `allianzid` = {$_GET['id']}";
		$erg = mysql_query($sql_query);
		if(!$erg) die("Falscher Query: ".$sql_query);
		while($out = mysql_fetch_object($erg))
		{
			SendBericht($out->userid, "Deine Allianz wurde aufgelöst!", "Deine Allianz, {$allianzname}, wurde aufgel&ouml;st!");
					
		}
		mysql_free_result($erg);
		$sql_query = "DELETE FROM `allianzen` WHERE `id` = {$_GET[id]}";
		mysql_query($sql_query);
 
		$sql_query = "DELETE FROM `allianz_user` WHERE `allianzid` = {$_GET[id]}";
		mysql_query($sql_query);
		mysql("UPDATE `allianzen_foren` SET `allianzid` = '-1' WHERE `allianzid` = {$_GET[id]} ;")
?>		
		Allianz <?=$allianzname?> wurde gel&ouml;scht!
		<br>
		<br>
		<h1>Weitere Allianzen l&ouml;schen</h1>
		<br>
<?php

	}
	else
	{
?>		
		Allianz konnte nicht gefunden werden!
		<h1>Allianzen l&ouml;schen</h1>
		<br>
<?php
	}
}
else
{
?>
	<h1>Allianzen l&ouml;schen</h1>
	<br>
<?php
}
?>

<table>
	<tr>
	 <td>Allianzen:</td> <td>&nbsp;</td>
	<tr>
	
<?php

$sql_query = "SELECT `allianzname`, `id` FROM `allianzen` ORDER BY `id`";
$erg = mysql_query($sql_query);
if(!$erg) die("Falscher Query: ".$sql_query);
while($out = mysql_fetch_object($erg))
{
?>
	<tr>
	 <td><?=$out->allianzname?></td> <td><a href="Allianzen_loeschen.php?id=<?=$out->id?>" onclick="if(confirm('Wiklich die Allianz löschen?') == false){return false;}">l&ouml;schen</a></td>
	<tr>
	
<?php	
}
mysql_free_result($erg);
?>
 
</center>
		</td>
	</tr>
</table>
		<?php
	}
?>
</body>
</html>