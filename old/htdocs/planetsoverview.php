<?php include_once("data/funcs.php"); DrawHeader("Übersicht"); ?>
<?php 
/*$grad = 0;
$entfernung = 0;
for($entfernung = 0;$entfernung<20;$entfernung+=1){
	while($grad < 360){
		echo "<div style=\"width:30px; height:30px; position:absolute; top:".(round(cos($grad)*$entfernung)*30+500)."px; left:".(round(sin($grad)*$entfernung)*30+1000)."px; background-color:#fdd;\">".round(cos($grad)*$entfernung)."|".round(sin($grad)*$entfernung)."</div>\n";
		$grad += 360/(($entfernung+1)*pi())*4;
	}
	$grad -= 360;
}*/
$sql = "SELECT * FROM planets WHERE userid = '{$_SESSION['userid']}' ORDER BY planetname, points DESC";
$erg = mysql_query($sql);
if(!isset($_GET['autobuild'])){
	?>
	<h1>Planeten:</h1>
	<table border="1">
	<tr>
	<td>Punkte</td>
	<td>Moral</td>
	<td>Planet</td>
	<td>Platin</td>
	<td>Plasma</td>
	<td>Plasmid</td>
	<td>Stahl</td>
	<td>Energie</td>
	<td>Nahrung</td>
	<td>Lagermenge</td>
	<td>ausbauen</td>
	<td>rekrutieren</td>
	</tr>
	<?php
	while($out = mysql_fetch_object($erg)){
		$nobuild = 0;
		$buildmin = array();
		$lastend = 0;
		$erg2 = mysql_query("SELECT * FROM `event_build` WHERE village='{$out->id}' ORDER BY end ");
		while($out2 = mysql_fetch_object($erg2)){
			$lastend = $out2->end;
			$nobuild++;
			$buildmin[$out2->building]++;
		}
		mysql_free_result($erg2);
		$buildbool = false;
		if(($nobuild < 5) && ($out->points != 10000)){
			foreach($builds as $build => $cont){
				if($out->$cont['intname'] < $cont["maxlevel"]){
					if((round($cont["Platin"]*pow($cont["Faktor"]["Build"],$out->$cont['intname']+$buildmin[$cont['intname']]))<=(int)($out->platin/1000)) &&
						(round($cont["Energie"]*pow($cont["Faktor"]["Build"],$out->$cont['intname']+$buildmin[$cont['intname']]))<=(int)($out->energie/1000)) &&
						(round($cont["Stahl"]*pow($cont["Faktor"]["Build"],$out->$cont['intname']+$buildmin[$cont['intname']]))<=(int)($out->stahl/1000)) &&
						(round($cont["Plasma"]*pow($cont["Faktor"]["Build"],$out->$cont['intname']+$buildmin[$cont['intname']]))<=(int)($out->plasma/1000)) &&
						(round($cont["Plasmid"]*pow($cont["Faktor"]["Build"],$out->$cont['intname']+$buildmin[$cont['intname']]))<=(int)($out->plasmid/1000)) &&
						(round($cont["Nahrung"]*pow($cont["Faktor"]["Build"],$out->$cont['intname']+$buildmin[$cont['intname']]))<=(int)($out->nahrung/1000))){
						$buildbool = true;
						break;
					}
				}
			}
		}
		
		
		$unums = 0;
		foreach($units as $unitname => $cont){
			if($unitname == 'president') continue;
			$unums += (int)min(	(int)($out->platin/1000)/	(($cont['Platin'])	?$cont['Platin']	:0.00000001),
								(int)($out->plasma/1000)/	(($cont['Plasma'])	?$cont['Plasma']	:0.00000001),
								(int)($out->nahrung/1000)/	(($cont['Nahrung'])	?$cont['Nahrung']	:0.00000001),
								(int)($out->energie/1000)/	(($cont['Energie'])	?$cont['Energie']	:0.00000001),
								(int)($out->plasmid/1000)/	(($cont['Plasmid'])	?$cont['Plasmid']	:0.00000001),
								(int)($out->stahl/1000)/	(($cont['Stahl'])	?$cont['Stahl']		:0.00000001),
								($buildings["versorgtePlaetze"][$buildings["bauer"]["Stufe"]]-$unumsreal['troopsGes'])/ $cont['Bevoelkerung']);
		}
	?>
	<tr>
	<td><?=$out->points?></td>
	<td><?=round($out->moral*100)?>%</td>
	<td><a href="planet.php?id=<?=$out->id?>"><?=$out->planetname?> (<?=$out->xcoords?>|<?=$out->ycoords?>)</a></td>
	<td><?=((int)($out->platin/1000)>$buildings["lagermenge"][$out->lagerhalle])?$buildings["lagermenge"][$out->lagerhalle]:(int)($out->platin/1000)?></td>
	<td><?=((int)($out->plasma/1000)>$buildings["lagermenge"][$out->lagerhalle])?$buildings["lagermenge"][$out->lagerhalle]:(int)($out->plasma/1000)?></td>
	<td><?=((int)($out->plasmid/1000)>$buildings["lagermenge"][$out->lagerhalle])?$buildings["lagermenge"][$out->lagerhalle]:(int)($out->plasmid/1000)?></td>
	<td><?=((int)($out->stahl/1000)>$buildings["lagermenge"][$out->lagerhalle])?$buildings["lagermenge"][$out->lagerhalle]:(int)($out->stahl/1000)?></td>
	<td><?=((int)($out->energie/1000)>$buildings["lagermenge"][$out->lagerhalle])?$buildings["lagermenge"][$out->lagerhalle]:(int)($out->energie/1000)?></td>
	<td><?=((int)($out->nahrung/1000)>$buildings["lagermenge"][$out->lagerhalle])?$buildings["lagermenge"][$out->lagerhalle]:(int)($out->nahrung/1000)?></td>
	<td><?=$buildings["lagermenge"][$out->lagerhalle]?></td>
	<td><a href="senat.php?changeplanet=<?=$out->id?>"><?=$buildbool?'Ja':'Nein'?></a></td>

	<td><a href="raumhafen.php?changeplanet=<?=$out->id?>"><?=($unums && !SqlObjQuery("SELECT * FROM `event_recruit` WHERE planet='{$out->id}'") && $out->raumhafen)?'Ja':'Nein'?></a></td>
	</tr>
	<?php
	}
}
else{
	?>
	<h1>Automatisches ausbauen</h1>
	<?php
	$bp = false;
	while($out = mysql_fetch_object($erg)){
		$nobuild = 0;
		$buildmin = array();
		$lastend = 0;
		$erg2 = mysql_query("SELECT * FROM `event_build` WHERE village='{$out->id}' ORDER BY end ");
		while($out2 = mysql_fetch_object($erg2)){
			$lastend = $out2->end;
			$nobuild++;
			$buildmin[$out2->building]++;
		}
		mysql_free_result($erg2);
		$buildable = 0;
		$buildarrays = array();
		if(($nobuild < 5) && ($out->points < 3500)){
			foreach($builds as $build => $cont){
				if($out->$cont['intname'] < $cont["maxlevel"]){
					if((round($cont["Platin"]*pow($cont["Faktor"]["Build"],$out->$cont['intname']+$buildmin[$cont['intname']]))<=(int)($out->platin/1000)) &&
						(round($cont["Energie"]*pow($cont["Faktor"]["Build"],$out->$cont['intname']+$buildmin[$cont['intname']]))<=(int)($out->energie/1000)) &&
						(round($cont["Stahl"]*pow($cont["Faktor"]["Build"],$out->$cont['intname']+$buildmin[$cont['intname']]))<=(int)($out->stahl/1000)) &&
						(round($cont["Plasma"]*pow($cont["Faktor"]["Build"],$out->$cont['intname']+$buildmin[$cont['intname']]))<=(int)($out->plasma/1000)) &&
						(round($cont["Plasmid"]*pow($cont["Faktor"]["Build"],$out->$cont['intname']+$buildmin[$cont['intname']]))<=(int)($out->plasmid/1000)) &&
						(round($cont["Nahrung"]*pow($cont["Faktor"]["Build"],$out->$cont['intname']+$buildmin[$cont['intname']]))<=(int)($out->nahrung/1000))){
						$buildable++;
						$buildarrays[$buildable-1] = $cont;
					}
				}
			}
		}
		if($buildable){
			$cnt = $buildarrays[rand(0,$buildable-1)];
			?>
<script type="text/javascript">
	function reload(){
		window.location.href = window.location.href;
	}
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
            }
        }  
    }
	if (req == null) alert("Error creating request object!");
                  
    //anfrage erstellen (GET, url ist localhost,
    //request ist asynchron      
    var url = '<?=$worldroot?>ausbauen.php?changeplanet=<?=$out->id?>';
	//window.alert(url);
    req.open("GET", url, true);

    //Beim abschliessen des request wird diese Funktion ausgeführt
    req.onreadystatechange = next;
  
    req.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    req.send(null);
	
	function next(){
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
				}
			}  
		}
		if (req == null) alert("Error creating request object!");
					  
		//anfrage erstellen (GET, url ist localhost,
		//request ist asynchron      
		var url = '<?=$worldroot?>ausbauen.php?building=<?=$cnt['intname']?>';
		//window.alert(url);
		
		
		
		
		req.open("GET", url, true);

		//Beim abschliessen des request wird diese Funktion ausgeführt
		req.onreadystatechange = reload;
	  
		req.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		req.send(null);
	}
</script>
			<?php
			$bp = true;
			break;
		}
		else{
		}
	}
}
if(!$bp && isset($_GET['autobuild'])){
	?>
<br/>
Nichts zu bauen.
<script type="text/javascript">
	window.setTimeout('window.location.href = window.location.href;',30000);
</script>
	<?php
}else{echo '</table>'; }
mysql_free_result($erg);
?>
<br/><a href="show_attacks.php"><b>Angriffe</b></a>
<?php DrawFooter(); ?>