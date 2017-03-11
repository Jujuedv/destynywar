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
		<script type="text/javascript">
<?php if(!isset($_GET['real'])){ ?>
	Check = confirm("Wirklich alle Allianzen löschen?");
	if (Check == false)	window.location.href = "../listing.php";
	else 				window.location.href = "<?=$_SERVER['SCRIPT_NAME']?>?real";
<?php } ?>
</script>
<?php
if(isset($_GET['real'])){
	mysql_query("TRUNCATE TABLE allianz_user");
	mysql_query("TRUNCATE TABLE allianzen");
	mysql_query("TRUNCATE TABLE allianzen_beitraege");
	mysql_query("TRUNCATE TABLE allianzen_einladungen");
	mysql_query("TRUNCATE TABLE allianzen_foren");
	mysql_query("TRUNCATE TABLE allianzen_themen");
	mysql_query("TRUNCATE TABLE allianzen_themen_gelesen");
}
?>
<h1>Fertig!</h1>
</center>
		</td>
	</tr>
</table>
		<?php
	}
?>
</body>
</html>