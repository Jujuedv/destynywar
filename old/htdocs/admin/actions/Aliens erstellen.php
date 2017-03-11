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
    include_once("../../data/settings.php");
	
	  function SqlObjQuery($sql_query){
	$erg = mysql_query($sql_query);
	if(!$erg) die("Falscher Query: ".$sql_query);
	$out = mysql_fetch_object($erg);
	mysql_free_result($erg);
	return $out;
}

function createPlanet($user = -1,$name = "Alienplanet"){ //user == -1 = Ohne Besitzer
	if($user == -1) $name = "Alienplanet";
	$sql = "SELECT * FROM variablen where variable = 'grad'";
	$erg = mysql_query($sql);
	$out = mysql_fetch_object($erg);
	$grad = $out->inhalt;
	mysql_free_result($erg); 
	$sql = "SELECT * FROM variablen where variable = 'entfernung'";
	$erg = mysql_query($sql);
	$out = mysql_fetch_object($erg);
	$entfernung = $out->inhalt;
	mysql_free_result($erg);
	
	if($grad >= 360){
		$grad -= 360;
		$entfernung++;
	}
	$x = (round(cos($grad)*$entfernung));
	$y = (round(sin($grad)*$entfernung));
	$grad += 360/(($entfernung+1)*pi())*4;
	
	$sql = "UPDATE variablen SET inhalt = '{$grad}' WHERE variable='grad'";
	mysql_query($sql);
	$sql = "UPDATE variablen SET inhalt = '{$entfernung}' WHERE variable='entfernung'";
	mysql_query($sql);
	if(SqlObjQuery("SELECT * FROM `planets` WHERE `xcoords` = {$x} AND `ycoords` = {$y}")){
		createPlanet($user,$name);
	}
	else{
		$sql = "INSERT INTO `planets` ( `userid` , `planetname` , `xcoords` , `ycoords` , `platin` , `energie` , `stahl` , `plasma` , `plasmid` , `nahrung` , `lagerhalle` , `biolabor` , `senat` , `miene` , `reaktor` , `reaktorplasmaverhaeltnis` , `schmelze` , `markt` , `bauer` , `schildgenerator` , `raumhafen` , `lastplus`)
			VALUES (
			'{$user}', '{$name}', '{$x}', '{$y}', '1000000', '1000000', '1000000', '1000000', '1000000', '1000000', '0', '0', '1', '0', '0', '50', '0', '0', '0', '0', '0',".time()."
			);";
		mysql_query($sql);
		$out = SqlObjQuery("SELECT count(id) as cnt FROM planets");
		if(!($out->cnt % 3)) createPlanet();
		mysql_query("INSERT INTO `variablen` ( `variable` , `inhalt` ) VALUES ( 'punkte_akt', '');");
	}
}
	
	
    if(!empty($_GET['count']) && is_numeric($_GET['count'])){
        for($i = 0; $i < $_GET['count']; $i++){
            createPlanet();
        }
		echo "Planeten Erstellt<br/>";
    }
?>
<form action='Aliens%20erstellen.php' method='GET'>
Anzahl: <input type="text" name="count"><input type="submit" value="erstellen">
</form>

</center>
		</td>
	</tr>
</table>
		<?php
	}
?>
</body>
</html>