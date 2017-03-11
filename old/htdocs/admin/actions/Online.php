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
		

function getUserName($userid){
	$sql = "SELECT username FROM user WHERE id = '{$userid}'";
	$erg = mysql_query($sql);
	if($out = mysql_fetch_object($erg)){
		mysql_free_result($erg);
		return $out->username;
	}
	mysql_free_result($erg);
	return 0;
}


$erg = mysql_query("SELECT * FROM `user` WHERE `lastacces` >  ".(time()-60)." ORDER BY id");
while($out = mysql_fetch_object($erg)) {
	echo "<a href='../../player.php?id={$out->id}'>".getUserName($out->id)."</a><br/>\n";
}
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