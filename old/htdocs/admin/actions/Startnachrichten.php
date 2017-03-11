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
		<h2>Neue Nachricht erstellen:</h2>
		<form action="Startnachrichten.php?new" method="POST">
			<textarea id="txt" name="txt" rows="10" cols="50"></textarea><br />
			<input type="submit" value="Erstellen" /> 
		</form>
		<h2>Nachrichten:</h2>
		<table border="1">
<?php
	if(is_numeric($_GET['delete'])) mysql_query("UPDATE `messages` SET `del` = 1 WHERE `id` = {$_GET['delete']};");
	if(is_numeric($_GET['restore'])) mysql_query("UPDATE `messages` SET `del` = 0 WHERE `id` = {$_GET['restore']};");
	if(!empty($_POST['txt'])) mysql_query("INSERT INTO `messages` ( `message` , `del` ) VALUES ('".nl2br(htmlentities($_POST['txt'], ENT_QUOTES))."' , 0)");
	$erg = mysql_query("SELECT * FROM `messages` ORDER BY del, id DESC;");
	while($out = mysql_fetch_object($erg)) {
		$message = $out->message;
?>
	<tr>
		<td>
			<?php
			if($out->del) echo "gel&oumlscht";
			?>
		</td>
		<td class="tp" style="text-align:center;">
			<?=$message?>
		</td>
		<td class="tp" style="text-align:center;">
			<?php
			if(!$out->del){
			?>
			<a href="Startnachrichten.php?delete=<?=$out->id?>">l&ouml;schen</a>
			<?php
			}else{
			?>
			<a href="Startnachrichten.php?restore=<?=$out->id?>">herstellen</a>
			<?php
			}
			?>
		</td>
	</tr>
<?php
	}
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