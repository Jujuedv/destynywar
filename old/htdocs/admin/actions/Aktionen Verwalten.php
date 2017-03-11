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
			echo "<li><a href='$filename'>".$ohnePHP[0]."</a></li>\n";
		}
		mysql_connect("localhost", "root","jgames") or die ("Fehler: 1");
		mysql_select_db("dw") or die ("Fehler: 2");
		if(isset($_GET['del'])  && !empty($_GET['file'])){
			$name = $_GET['file'];
			$name = preg_replace("*[\.\\\\/]*","",$name);
			unlink($name.".php");
			?>
<script type="text/javascript">
	window.location.href = "../listing.php";
</script>
			<?php
		}
		if(isset($_GET['add']) && !empty($_POST['name']) && !empty($_POST['inhalt'])){
			$name = $_POST['name'];
			$name = preg_replace("*[\.\\\\/]*","",$name);
			$file = fopen  ( $name.".php" ,  "w+"  );
			fwrite($file, <<<TEXT
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
	if(!\$_SESSION['admin_login']){?>
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
		foreach (glob("*.php") as \$filename) {
			\$ohnePHP = explode( "." , \$filename , -1 );
			echo "<li><a href='\$filename'>".\$ohnePHP[0]."</a></li>\n";
		}
		mysql_connect("localhost", "root","jgames") or die ("Fehler: 1");
		mysql_select_db("dw") or die ("Fehler: 2");
		?>
		</ul>
		</td>
		<td valign="top">
		<center>
		
TEXT
);
$cont = preg_replace("*\\\\*","",$_POST['inhalt']);
//$cont = preg_replace("*\'*","",$cont);
fwrite($file,$cont);
//echo $cont;
fwrite($file, <<<TEXT

</center>
		</td>
	</tr>
</table>
		<?php
	}
?>
</body>
</html>
TEXT
);
			fclose($file);
			?>
<script type="text/javascript">
	window.location.href = "../listing.php";
</script>
			<?php
		}
		?>
		</ul>
	</td>
	<td valign="top">
		<center>
		<h1>Neue Aktion einf&uuml;gen</h1>
		<form action="Aktionen%20Verwalten.php?add" method="POST">
		Name:<input name="name" id="name"/><br/>
		Inhalt:<textarea id="inhalt" name="inhalt" rows="20" cols="100"></textarea><br/>
		<input type="submit" value="Einf&uuml;gen"/>
		</form><br/><br/><br/><br/>
		<h1>Aktion l&ouml;schen</h1>
		<ul>
		<?php
		foreach (glob("*.php") as $filename) {
			$ohnePHP = explode( "." , $filename , -1 );
			echo "<li><a href='Aktionen%20Verwalten.php?del&file={$ohnePHP[0]}'>".$ohnePHP[0]."</a></li>\n";
		}
		?>
		</ul>
		</center>
		</td>
	</tr>
</table>
		<?php
	}
?>
</body>
</html>