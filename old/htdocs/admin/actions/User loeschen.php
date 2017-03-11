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
 
 if(is_numeric($_GET['id']))
 { 
 $sql_query = "SELECT `username` FROM `user` WHERE `id` = {$_GET['id']}";
 $erg = mysql_query($sql_query);
 if(!$erg) $username = "User nicht Vorhanden";
 else{
 $out = mysql_fetch_object($erg);
 $username = $out->username;
 }
 mysql_free_result($erg);
 
 if($username != "User nicht Vorhanden")
 {
 
?>
 <script type="text/javascript">
 //Check = confirm("Wirklich den User <?=$username?> löschen?");
 //if (Check == false)
 // window.location.href = "User loeschen.php";

 </script>
 
<?php
 $sql_query = "DELETE FROM `event_build` WHERE `village` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_GET['id']})";
 mysql_query($sql_query);
 $sql_query = "DELETE FROM `event_recruit` WHERE `planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_GET['id']})";
 mysql_query($sql_query);
 
 $sql_query = "UPDATE `planets` SET `userid` = -1, `planetname` = 'Alienplanet' WHERE `userid` = {$_GET['id']}";
 mysql_query($sql_query);
 $sql_query = "DELETE FROM `allianz_user` WHERE `userid` = {$_GET['id']}";
 mysql_query($sql_query);
 $sql_query = "DELETE FROM `user` WHERE `id` = {$_GET['id']}";
 mysql_query($sql_query);

  echo  "<h1>Weitere User l&ouml;schen:<h1>";

 }
 else
 {
  echo  "<h1>User l&ouml;schen:<h1>";
 }
 
 }
 else
 {
  echo  "<h1>User l&ouml;schen:<h1>";
 }
 
 ?>
<table>
 <tr>
  <td>User:</td> <td>&nbsp;</td>
 <tr>
 
 
 
 <?php
 $sql_query = "SELECT `username`, `id` FROM `user` ORDER BY `points` DESC ,`id` ASC";
 $erg = mysql_query($sql_query);
 if(!$erg) die("Falscher Query: ".$sql_query);
 ?>


 <?php
 while($out = mysql_fetch_object($erg)){ 
 
 
?>  
 <tr>
 <td><?=$out->username?></td> <td><a href="User%20loeschen.php?id=<?=$out->id?>">l&ouml;schen</a> </td> 
 </tr>
<?php
 };
  mysql_free_result($erg);
?>

</table>
</center>
		</td>
	</tr>
</table>
		<?php
	}
?>
</body>
</html>