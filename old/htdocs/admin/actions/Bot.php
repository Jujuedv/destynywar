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
		<table>
<tr><td><b>User:</b></td><td><b>Bot</b></td><td><b>einschalten/ausschalten</b></td><tr>
<?php
	if(isset($_GET['stop'])) mysql_query("TRUNCATE TABLE bots");
	if(is_numeric($_GET['id'])){
		$erg2 = mysql_query("SELECT `user` FROM bots WHERE user=".$_GET['id']);
		if(!mysql_fetch_object($erg2)){
			mysql_query("INSERT INTO bots( `user` ) VALUES ( ".$_GET['id']." )");
		}
		else mysql_query("DELETE FROM bots WHERE `user` = ".$_GET['id']."");
	}
	$sql_query = "SELECT `user`.`username`, `user`.`id`, `bots`.`user`  FROM `user` LEFT JOIN `bots` ON `user`.`id` = `bots`.`user` ORDER BY `user`.`points` DESC ,`user`.`id` ASC";
	$erg = mysql_query($sql_query);
	if(!$erg) die("Falscher Query: ".$sql_query);
?>


<?php
	while($out = mysql_fetch_object($erg)){ 
?>  
 <tr>
 <td><?=$out->username?></td><td><?=($out->id == $out->user)?"Ja":"Nein"?></td><td><a href="Bot.php?id=<?=$out->id?>">&auml;ndern</a> </td> 
 </tr>
<?php
 };
  mysql_free_result($erg);
?>

</table><br/><br/>
<a href="Bot.php?stop">Bot stoppen</a>
</center>
		</td>
	</tr>
</table>
		<?php
	}
?>
</body>
</html>