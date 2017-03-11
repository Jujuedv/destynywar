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
		function SqlObjQuery($sql_query){
			$erg = mysql_query($sql_query);
			$out = mysql_fetch_object($erg);
			mysql_free_result($erg);
			return $out;
		}
		include("../../data/units.php");
		
		//$erg = mysql_query("SELECT * FROM planets as P,event_troops_defense as D, event_troops_back as B, event_troops_attack as A, event_recruit as R ");
		
		
		$erg = mysql_query("SELECT * FROM planets");
		while($out = mysql_fetch_object($erg)){
			$troops = array();
			foreach($units as $key => $element_value){
				$troops[$key] = $out->$key;
			}
			$out2 = SqlObjQuery("SELECT SUM(abfangjaeger) as abfangjaeger, SUM(techniker) as techniker, SUM(segler) as segler, SUM(stosstruppe) as stosstruppe, SUM(drone) as drone, SUM(kreuzer) as kreuzer FROM event_troops_defense WHERE to_planet = {$out->id}");
			foreach($units as $key => $element_value){
				$troops[$key] += $out2->$key;
			}
			$out2 = SqlObjQuery("SELECT SUM(abfangjaeger) as abfangjaeger, SUM(techniker) as techniker, SUM(segler) as segler, SUM(stosstruppe) as stosstruppe, SUM(drone) as drone, SUM(kreuzer) as kreuzer FROM event_troops_attack WHERE from_planet = {$out->id}");
			foreach($units as $key => $element_value){
				$troops[$key] += $out2->$key;
			}
			$out2 = SqlObjQuery("SELECT SUM(abfangjaeger) as abfangjaeger, SUM(techniker) as techniker, SUM(segler) as segler, SUM(stosstruppe) as stosstruppe, SUM(drone) as drone, SUM(kreuzer) as kreuzer FROM event_troops_back WHERE to_planet = {$out->id}");
			foreach($units as $key => $element_value){
				$troops[$key] += $out2->$key;
			}
			$erg2 = mysql_query("SELECT * FROM event_recruit WHERE planet = {$out->id}");
			while($out2 = mysql_fetch_object($erg2)){
				$troops[$out2->type] += $out2->unitsleft+1;
			}
			mysql_free_result($erg2);
			$bevoelkerung = 0;
			foreach($units as $key => $element_value){
				$bevoelkerung += $troops[$key]*$unit[$key]['Bevoelkerung'];
			}
			mysql_query("UPDATE `planets` SET `troopsGes` = {$bevoelkerung} WHERE `planets`.`id` = {$out->id}");
		}
		mysql_free_result($erg);
?>
Farm neu Berechnet!
</center>
		</td>
	</tr>
</table>
		<?php
	}
?>
</body>
</html>