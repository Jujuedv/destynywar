<?php include_once("data/funcs.php"); DrawHeader("Karte"); ?>
<?php
	
	function abstand($x1,$y1,$x2,$y2){
		return sqrt(($x1-$x2)*($x1-$x2)+($y1-$y2)*($y1-$y2));
	}
	
	
	function vektorangel($x1,$y1,$x2,$y2){
		//echo abstand($x1,$y1,$x2,$y2),":::",($x2-$x1);
		return acos(($x2-$x1)/abstand($x1,$y1,$x2,$y2));
	}
	
	
	function rotate($x1,$y1,$x2,$y2){
		$angleInRadians = acos(($x1-$x2)/abstand($x1,$y1,$x2,$y2));
		$angleInDeg = rad2deg($angleInRadians);
		if($y1 - $y2 < 0){
			$angleInRadians = acos(($x2-$x1)/abstand($x1,$y1,$x2,$y2));
			$angleInDeg = rad2deg($angleInRadians)+180;
			
		}
		return $angleInDeg;
	}
	function calculateAngle($c,$a,$b)
	{
		$angleInRadians=acos((pow($a,2) + pow($b,2) - pow($c,2)) / (2 * $a * $b));
		return rad2deg($angleInRadians);
	}
	
	
	
	$out = SqlObjQuery("SELECT * FROM user WHERE id= {$_SESSION['userid']}");
	if(isset($_GET['sattacks']) && ($out->angriffspfeile == 0)){
		mysql_query("UPDATE user SET angriffspfeile = 1 WHERE id = {$_SESSION['userid']}");
	}
	else{
		if(!isset($_GET['sattacks']) && (isset($_GET['karteform']))){
			mysql_query("UPDATE user SET angriffspfeile = 0 WHERE id = {$_SESSION['userid']}");
		}
		else if($out->angriffspfeile == 1) $_GET['sattacks'] = 'sattacks';
	}
	
	$selfstufe;
	if($out->points <= $settings['stufe322']) 		$selfstufe = 3;
	elseif($out->points <= $settings['stufe221'])	$selfstufe = 2;
	else							$selfstufe = 1;
	
	
	$sql = "SELECT * FROM planets WHERE id='{$_SESSION['planetid']}'";
	$erg = mysql_query($sql);
	$out = mysql_fetch_object($erg);
	$x = !is_numeric($_GET['x'])?$out->xcoords:$_GET['x'];
	$y = !is_numeric($_GET['y'])?$out->ycoords:$_GET['y'];
	mysql_free_result($erg);
	$extraparams = '';
	$extraparamsj = !empty($extraparams)?$extraparams.'&':'';
	$aufeinmal = 28;
	$fieldsize = 15;
	
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
			$level;
			if($out->points > 6000) 	$level = 3;
			elseif($out->points > 3000)	$level = 2;
			else						$level = 1;
			$stufe;
			if($out2->points <= $settings['stufe322']) 		$stufe = 3;
			elseif($out2->points <= $settings['stufe221'])	$stufe = 2;
			else							$stufe = 1;
			$skin = $out->id%3+1;
			$type;
			if($out->userid == $_SESSION['userid'])												$type = "A";
			elseif($out3->allianzid == $_SESSION['allianz'] && !empty($_SESSION['allianz']))	$type = "B";
			else 																				$type = "C";
			if(($out->xcoords == 0) && ($out->ycoords == 0)){ $type=0; $level=0; $skin=0;}
			$planets[] = array("x"=>$out->xcoords-$x+$aufeinmal-1,"y"=>$out->ycoords-$y+$aufeinmal-1,"rx"=>$out->xcoords,"ry"=>$out->ycoords,"img" => "pics/Planeten/".$type."_".$level."_".$skin.".gif", "type" => 1, "id" => $out->id, "data" => array("name" => $out->planetname,"user" => preg_replace("~ ~", "&nbsp;",$out2->username),"punkte" => $out->points, "userpunkte" => $out2->points, "allianz" => ($tmp = GetAllianzName($out3->allianzid))?$tmp:'null'), "cut" => ($selfstufe != $stufe)?true:false);
			mysql_free_result($erg2);
			mysql_free_result($erg3);
		}
		else {
			$level;
			if($out->points > 6000) 	$level = 3;
			elseif($out->points > 3000)	$level = 2;
			else						$level = 1;
			$skin = $out->id%3+1;
			$planets[] = array("x"=>$out->xcoords-$x+$aufeinmal-1,"y"=>$out->ycoords-$y+$aufeinmal-1,"rx"=>$out->xcoords,"ry"=>$out->ycoords,"img" => "pics/Planeten/D_".$level."_".$skin.".gif", "type" => 1, "id" => $out->id, "data" => array("name" => $out->planetname,"user" => "null","punkte" => $out->points, "userpunkte" => "null", "allianz" => "null"), "cut" => false);
		}	
	}
	mysql_free_result($erg);
		?>

<?php
if(!isset($_GET['noid'])) {
?>
<script type="text/javascript">
function showdetails(name,user,allianz,punkte,allianzenpunkte,userpunkte){
	var str = "<tr> <td colspan=\"3\" rowspan=\"1\" style=\"background-color:#009;\">"+name+"("+punkte+"P.)</td></tr>";
	if(user != 'null') str+= "<tr><td style=\"background-color:#009;\">Spieler:</td><td style=\"background-color:#009;\">"+user+"</td><td style=\"background-color:#009;\">("+userpunkte+"P.)</td></tr><tr>";
	if(allianz != 'null') str+= "<td style=\"background-color:#009;\">Allianz:</td><td style=\"background-color:#009;\">"+allianz+"</td><td style=\"background-color:#009;\">("+allianzenpunkte+"P.)</td></tr>";
	//else str+="<td style=\"background-color:#009;\">Allianz:</td><td style=\"background-color:#009;\" colspan=\"2\">keine</td>";
	document.getElementById("mapdetails").innerHTML = str;
}

function movemap(Element) {
  document.getElementById("mapdetails").style.top = (Element.clientY-10)+"px";
  document.getElementById("mapdetails").style.left = (Element.clientX+10)+"px"; 
  return true;
}

function sendKarte(){
    var req = null;
    try{
       req = new XMLHttpRequest();
    }
    catch (e){
        try{
            req = new ActiveXObject("Msxml2.XMLHTTP");
        } 
        catch (e){
            try{
                req = new ActiveXObject("Microsoft.XMLHTTP");
            } 
            catch (failed){
                req = null;
                alert("in");
            }
        }  
    }
    if(req){
        req.open("GET", '<?=$worldroot?>karte.php?noid&x='+($('x').value)+'&y='+($('y').value)+'&<?=isset($_GET['sattacks'])?'&sattacks=sattacks':''?>', true);
		xt = $('x').value;
		yt = $('y').value;
        req.onreadystatechange = function(){ 
            switch(req.readyState) {
                case 4:
                    if(req.status!=200) { 
                    }else{
                        var html = req.responseText;
                        document.getElementById('kartesaver').innerHTML = html;
						//alert(document.getElementsByName('map_mover')[1].innerHTML);
                        document.getElementsByName('map_mover')[0].innerHTML = document.getElementsByName('map_mover')[1].innerHTML;
						document.getElementsByName('map_mover')[0].style = "position:relative ;left:-<?=($aufeinmal-((int)($fieldsize/2)+1))*45?>px;top:-<?=($aufeinmal-((int)($fieldsize/2)+1))*45?>px;-moz-user-select:none;";
						x = xt;
						y = yt;
					}
                    break;
                default:
                    return false;
                break;     
            }
        }
        req.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        req.send();
    }else alert("Kann nicht automatisch nachladen!");
}
//window.setInterval("sendKarte();",5000);

document.onmousemove = movemap;
</script>
<table id="mapdetails" style="position:fixed; z-index:99;" border="0" cellpadding="2" cellspacing="2" >
	<!--<tr>
		<td colspan="3" rowspan="1" style="background-color:#009;">NAME(P.)</td>
	</tr>
	<tr>
		<td style="background-color:#009;">Spieler:</td>
		<td style="background-color:#009;">SPIELER</td>
		<td style="background-color:#009;">(P.)</td>
	</tr>
	<tr>
		<td style="background-color:#009;">Allianz:</td>
		<td style="background-color:#009;">ALLIANZ</td>
		<td style="background-color:#009;">(P.)</td>
	</tr>height: 1485px;width:1485px;-->
</table>
<?php } ?>
<div <?php
if(!isset($_GET['noid'])) {
?>id="karte" <?php } ?> name="karte" style="background-image: url(pics/sternenhimmel.jpeg); cursor:cross; height: <?=((int)($fieldsize))*45?>px;width:<?=((int)($fieldsize))*45?>px;overflow:hidden;">
	<div <?php
if(!isset($_GET['noid'])) {
?>id="map_mover" <?php } ?>  name="map_mover" style="position:relative ;left:-<?=($aufeinmal-((int)($fieldsize/2)+1))*45?>px;top:-<?=($aufeinmal-((int)($fieldsize/2)+1))*45?>px;-moz-user-select:none;" >
		<div style="position: absolute;left:<?=(-1-$x+$aufeinmal-1)*45?>;top:<?=(-1-$y+$aufeinmal-1)*45?>;width:135px; height:135px;">
			<img src="pics/Planeten/Schwarzes%20Loch.gif"  style="width:135px; height:135px; border-style: none;z-index:-1;" />
		</div>
		
		
	<?php foreach($planets as $data){
		if($data["type"] == 1){
			$data['data']['name'] = preg_replace("~&#039;~","\\'",$data['data']['name']);
	?>
		<div style="position: absolute;left:<?=$data["x"]*45?>;top:<?=$data["y"]*45?>;width:45px; height:45px; z-index:97;">
			<a href="planet.php?id=<?=$data["id"]?>" style="border-style: none;"><img src="<?=$data["img"]?>"  style="width:45px; height:45px; border-style: none;" onmouseout="document.getElementById('mapdetails').innerHTML = '';" onmouseover="showdetails('<?=htmlentities($data['data']['name'])."({$data['rx']}|{$data['ry']}) "?>','<?=htmlentities($data['data']['user'])?>','<?=htmlentities($data['data']['allianz'])?>','<?=$data['data']['punkte']?>',<?=($tmp = GetAllianzPoints(GetAllianzId($data['data']['allianz'])))?$tmp:'null'?>,'<?=$data['data']['userpunkte']?>');" /></a>
		</div>
		<?php
			if($data["cut"]){
		?>
		<div style="position: absolute;left:<?=$data["x"]*45?>;top:<?=$data["y"]*45?>;width:45px; height:45px; z-index:98;">
			<a href="planet.php?id=<?=$data["id"]?>" style="border-style: none;"><img src="pics/cut.gif"  style="width:45px; height:45px; border-style: none;" onmouseout="document.getElementById('mapdetails').innerHTML = '';" onmouseover="showdetails('<?=htmlentities($data['data']['name'])."({$data['rx']}|{$data['ry']}) "?>','<?=htmlentities($data['data']['user'])?>','<?=htmlentities($data['data']['allianz'])?>','<?=$data['data']['punkte']?>',<?=($tmp = GetAllianzPoints(GetAllianzId($data['data']['allianz'])))?$tmp:'null'?>,'<?=$data['data']['userpunkte']?>');" /></a>
		</div>
	<?php 
		} 
		}
	}
	if(isset($_GET['sattacks'])){
		$erg = mysql_query("SELECT * FROM `event_troops_attack` WHERE `from_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']})");
		$count = 0;
		while($out = mysql_fetch_object($erg)){
			$outfrom = SqlObjQuery("SELECT * FROM planets WHERE id = {$out->from_planet}");
			$outtp = SqlObjQuery("SELECT * FROM planets WHERE id = {$out->to_planet}");
			$showx = $outfrom->xcoords;
			$showy = $outfrom->ycoords;
			$tox = $outtp->xcoords;
			$toy = $outtp->ycoords;
			?>
			<div style="position:absolute; left:<?=($showx-$x+$aufeinmal-1)*45?>;top:<?=($showy-$y+$aufeinmal-0.5-abstand($tox,$toy,$showx,$showy))*45?>; -moz-transform: rotate(<?=(rotate($showx,$showy,$tox,$toy)+270)%360?>deg); -moz-transform-origin: bottom; -webkit-transform: rotate(<?=(rotate($showx,$showy,$tox,$toy)+270)%360?>deg);-o-transform:rotate(<?=(rotate($showx,$showy,$tox,$toy)+270)%360?>deg); -o-transform-origin: bottom; -webkit-transform-origin:bottom; z-index:0;">  
				<img src="pics/pfeil1.gif" style=" height:<?=(abstand($tox,$toy,$showx,$showy))*45?>px; width:45px;"/>
			</div>
		<?php }
		$erg = mysql_query("SELECT * FROM `event_troops_attack` WHERE `to_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']})");
		$count = 0;
		while($out = mysql_fetch_object($erg)){
			$outfrom = SqlObjQuery("SELECT * FROM planets WHERE id = {$out->from_planet}");
			$outtp = SqlObjQuery("SELECT * FROM planets WHERE id = {$out->to_planet}");
			$showx = $outfrom->xcoords;
			$showy = $outfrom->ycoords;
			$tox = $outtp->xcoords;
			$toy = $outtp->ycoords;
			?>
			<div style="position:absolute; left:<?=($showx-$x+$aufeinmal-1)*45?>;top:<?=($showy-$y+$aufeinmal-0.5-abstand($tox,$toy,$showx,$showy))*45?>; -moz-transform: rotate(<?=(rotate($showx,$showy,$tox,$toy)+270)%360?>deg); -moz-transform-origin: bottom; -webkit-transform: rotate(<?=(rotate($showx,$showy,$tox,$toy)+270)%360?>deg);-o-transform:rotate(<?=(rotate($showx,$showy,$tox,$toy)+270)%360?>deg); -o-transform-origin: bottom; -webkit-transform-origin:bottom; z-index:0;">  
				<img src="pics/pfeil2.gif" style=" height:<?=(abstand($tox,$toy,$showx,$showy))*45?>px; width:45px;"/>
			</div>
		<?php } 
		$erg = mysql_query("SELECT * FROM `event_troops_attack` WHERE `from_planet` IN (SELECT `id` FROM `planets` WHERE `userid` <> {$_SESSION['userid']} and `userid` IN (SELECT userid FROM allianz_user WHERE allianzid = (SELECT allianzid FROM allianz_user WHERE userid = {$_SESSION['userid']}))) ");
		$count = 0;
		while($out = mysql_fetch_object($erg)){
			$outfrom = SqlObjQuery("SELECT * FROM planets WHERE id = {$out->from_planet}");
			$outtp = SqlObjQuery("SELECT * FROM planets WHERE id = {$out->to_planet}");
			$showx = $outfrom->xcoords;
			$showy = $outfrom->ycoords;
			$tox = $outtp->xcoords;
			$toy = $outtp->ycoords;
			?>
			<div style="position:absolute; left:<?=($showx-$x+$aufeinmal-1)*45?>;top:<?=($showy-$y+$aufeinmal-0.5-abstand($tox,$toy,$showx,$showy))*45?>; -moz-transform: rotate(<?=(rotate($showx,$showy,$tox,$toy)+270)%360?>deg); -moz-transform-origin: bottom; -webkit-transform: rotate(<?=(rotate($showx,$showy,$tox,$toy)+270)%360?>deg);-o-transform:rotate(<?=(rotate($showx,$showy,$tox,$toy)+270)%360?>deg); -o-transform-origin: bottom; -webkit-transform-origin:bottom; z-index:0;">  
				<img src="pics/pfeil3.gif" style=" height:<?=(abstand($tox,$toy,$showx,$showy))*45?>px; width:45px;"/>
			</div>
		<?php }
		$erg = mysql_query("SELECT * FROM `event_troops_attack` WHERE `to_planet` IN (SELECT `id` FROM `planets` WHERE `userid` <> {$_SESSION['userid']} and `userid` IN (SELECT userid FROM allianz_user WHERE allianzid = (SELECT allianzid FROM allianz_user WHERE userid = {$_SESSION['userid']})))");
		$count = 0;
		while($out = mysql_fetch_object($erg)){
			$outfrom = SqlObjQuery("SELECT * FROM planets WHERE id = {$out->from_planet}");
			$outtp = SqlObjQuery("SELECT * FROM planets WHERE id = {$out->to_planet}");
			$showx = $outfrom->xcoords;
			$showy = $outfrom->ycoords;
			$tox = $outtp->xcoords;
			$toy = $outtp->ycoords;
			?>
			<div style="position:absolute; left:<?=($showx-$x+$aufeinmal-1)*45?>;top:<?=($showy-$y+$aufeinmal-0.5-abstand($tox,$toy,$showx,$showy))*45?>; -moz-transform: rotate(<?=(rotate($showx,$showy,$tox,$toy)+270)%360?>deg); -moz-transform-origin: bottom; -webkit-transform: rotate(<?=(rotate($showx,$showy,$tox,$toy)+270)%360?>deg);-o-transform:rotate(<?=(rotate($showx,$showy,$tox,$toy)+270)%360?>deg); -o-transform-origin: bottom; -webkit-transform-origin:bottom; z-index:0;">  
				<img src="pics/pfeil4.gif" style=" height:<?=(abstand($tox,$toy,$showx,$showy))*45?>px; width:45px;"/>
			</div>
		<?php }
	} 
	?>
	</div>
</div>
<?php
if(!isset($_GET['noid'])) {
?>
<script type="text/javascript">
	var x = <?=$x?>;
	var y = <?=$y?>;
	var xo = <?=$x?>;
	var yo = <?=$y?>;
	var rx = x*45;
	var ry = y*45;
	var timer;
	var timeout;
	new Draggable("map_mover",{
	handle: 'karte',
	snap: function(x, y) {
			//xo = Math.round(x/45+<?=($aufeinmal-((int)($fieldsize/2)+1))?>);
			//yo = Math.round(y/45+<?=($aufeinmal-((int)($fieldsize/2)+1))?>);
			rx = (x < <?=($aufeinmal-((int)($fieldsize/2)+1))*45?>) ? (x > -<?=($aufeinmal-((int)($fieldsize/2)+1))*45*2?> ? x : -<?=($aufeinmal-((int)($fieldsize/2)+1))*45*2?> ) : <?=($aufeinmal-((int)($fieldsize/2)+1))*45?>;
			ry = (y < <?=($aufeinmal-((int)($fieldsize/2)+1))*45?>) ? (y > -<?=($aufeinmal-((int)($fieldsize/2)+1))*45*2?> ? y : -<?=($aufeinmal-((int)($fieldsize/2)+1))*45*2?>) : <?=($aufeinmal-((int)($fieldsize/2)+1))*45?>;
            return[ rx , ry ];
    },
	onEnd: function() {
			//alert(rx+"+"+ry+"::"+((Math.round(rx/45+<?=($aufeinmal-6)?>)-<?=($aufeinmal-6)?>)*45)+"+"+((Math.round(ry/45+<?=($aufeinmal-6)?>)-<?=($aufeinmal-6)?>)*45));
			//Effect.MoveBy('map_mover', ((Math.round(ry/45+<?=($aufeinmal-((int)($fieldsize/2)+1))?>)-<?=($aufeinmal-((int)($fieldsize/2)+1))?>)*45), ((Math.round(rx/45+<?=($aufeinmal-((int)($fieldsize/2)+1))?>)-<?=($aufeinmal-((int)($fieldsize/2)+1))?>)*45), {mode: 'absolute', duration: 0.5});
			//setTimeout("window.clearInterval(timer);",1000);
			//sleep(1000);
			window.clearInterval(timer);
			//timeout = setTimeout('window.location.href = "karte.php?<?=$extraparamsj?>x="+(x-Math.round(rx/45+<?=($aufeinmal-((int)($fieldsize/2)+1))?>))+"&y="+(y-Math.round(ry/45+<?=($aufeinmal-((int)($fieldsize/2)+1))?>))+"<?=isset($_GET['sattacks'])?'&sattacks=sattacks':''?>";',2500);
			timeout = setTimeout('sendKarte();',5000);
			//timeout = setTimeout(';');
	},
	onStart: function(){
			timer = window.setInterval("$('y').value = y-Math.round(ry/45+<?=($aufeinmal-((int)($fieldsize/2)+1))?>);$('x').value = x-Math.round(rx/45+<?=($aufeinmal-((int)($fieldsize/2)+1))?>);",10);
			window.clearTimeout(timeout);
    }
	
	});
</script>
<br/>
<form method="get" action="karte.php">
x:<input type="text" size="3" name="x" id="x" onclick="window.clearTimeout(timeout);" value="<?=$x?>"/>
y:<input type="text" size="3" name="y" id="y" onclick="window.clearTimeout(timeout);" value="<?=$y?>"/><br/>
<input type="hidden" name="karteform" id="karteform"/>
<input type="checkbox" name="sattacks" value="sattacks" <?=isset($_GET['sattacks'])?'checked="checked"':''?>> Angriffe zeigen<input type="submit" value="ok"/><br>
<input type="submit" value="zentrieren"/>
</form>
<?php } ?>
Angriffspfeile(Legende):<br/>
<table>
<tr>
<td><img src="pics/pfeil1.gif"/></td>
<td><img src="pics/pfeil2.gif"/></td>
<td><img src="pics/pfeil3.gif"/></td>
<td><img src="pics/pfeil4.gif"/></td>
</tr>
<tr>
<td><b>Angriffe&nbsp;von&nbsp;dir&nbsp;|&nbsp;</b></td>
<td><b>Angriffe&nbsp;auf&nbsp;dich&nbsp;|&nbsp;</b></td>
<td><b>Angriffe&nbsp;deiner&nbsp;Allianz&nbsp;|&nbsp;</b></td>
<td><b>Angriffe&nbsp;auf&nbsp;deine&nbsp;Allianz</b></td>
</tr>
</table>
<?php DrawFooter(); ?>