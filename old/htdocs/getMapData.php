<?php
	mysql_connect("localhost", "root","jgames") or die ("Fehler: 1");
	mysql_select_db("dw") or die ("Fehler: 2");
?>
<?php
	$sql = "SELECT * FROM planets WHERE id='{$_SESSION['planetid']}'";
	$erg = mysql_query($sql);
	$out = mysql_fetch_object($erg);
	$x = !is_numeric($_GET['x'])?$out->xcoords:$_GET['x'];
	$y = !is_numeric($_GET['y'])?$out->ycoords:$_GET['y'];
	mysql_free_result($erg);
	
	$aufeinmal = 17;
	
	$sql = "SELECT * FROM planets WHERE  (xcoords>'".($x-$aufeinmal)."') and (xcoords<'".($x+$aufeinmal)."') and (ycoords>'".($y-$aufeinmal)."') and (ycoords<'".($y+$aufeinmal)."')"; //dynamic loader
	//$sql = "SELECT * FROM planets"; //alles auf einmal
	$erg = mysql_query($sql);
	$planets = array();
	while($out = mysql_fetch_object($erg)){
		if($out->userid > 0){
			$sql = "SELECT * FROM user WHERE id='{$out->userid}'";
			$erg2 = mysql_query($sql);
			$out2 = mysql_fetch_object($erg2);
			$sql = "SELECT * FROM `allianz_user` WHERE userid='{$out->userid}'";
			$erg3 = mysql_query($sql);
			$out3 = mysql_fetch_object($erg3);
			$planets[] = array("x"=>$out->xcoords-$x+$aufeinmal-1,"y"=>$out->ycoords-$y+$aufeinmal-1,"rx"=>$out->xcoords,"ry"=>$out->ycoords,"img" => "pics/karteplanet1.jpg", "type" => 1, "id" => $out->id, "data" => array("name" => $out->planetname,"user" => preg_replace("~ ~", "&nbsp;",$out2->username),"punkte" => $out->points, "userpunkte" => $out2->punkte));
			mysql_free_result($erg2);
			mysql_free_result($erg3);
		}
		else {
			$planets[] = array("x"=>$out->xcoords-$x+$aufeinmal-1,"y"=>$out->ycoords-$y+$aufeinmal-1,"rx"=>$out->xcoords,"ry"=>$out->ycoords,"img" => "pics/karteplanetFree.jpg", "type" => 1, "id" => $out->id, "data" => array("name" => $out->planetname,"user" => "null","punkte" => $out->points, "userpunkte" => "null"));
		}	
	}
	mysql_free_result($erg);
		?>
	<?php foreach($planets as $data){
		if($data["type"] == 1){
	?>
		<div style="position: absolute;left:<?=$data["x"]*45?>;top:<?=$data["y"]*45?>;width:45px; height:45px;">
			<a href="planet.php?id=<?=$data["id"]?>" style="border-style: none;"><img src="<?=$data["img"]?>"  style="width:45px; height:45px; border-style: none;" onmouseout="document.getElementById('mapdetails').innerHTML = '';" onmouseover="showdetails('<?=$data['data']['name']."({$data['rx']}|{$data['ry']}) "?>','<?=$data['data']['user']?>',null,'<?=$data['data']['punkte']?>',null,'<?=$data['data']['userpunkte']?>');" /></a>
		</div>
	<?php 
		}
	}
	?>