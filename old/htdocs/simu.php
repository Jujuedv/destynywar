<?php include_once("data/funcs.php"); DrawHeader("Simulator"); ?>


<?php 
if(!empty($_POST['Send'])){
	//berechnen der Einheiten
	$angreifer = 0;
	$verteidiger = 0;
	require("data/units.php");
	require("data/buildings.php");
	//Deffwerte
	$deff['abfangjaeger'] = ($unit['abfangjaeger']['Deff']['ground']*($_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone'] ) + $unit['abfangjaeger']['Deff']['air']*($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] ))/(($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] + $_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone'])>0?($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] + $_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone']):1)*($buildings["schild"][(Int)$_POST['shield']]["bonus"]/100+1);
	$deff['techniker'] = ($unit['techniker']['Deff']['ground']*($_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone'] ) + $unit['techniker']['Deff']['air']*($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] ))/(($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] + $_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone'])>0?($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] + $_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone']):1)*($buildings["schild"][(Int)$_POST['shield']]["bonus"]/100+1);
	$deff['segler'] = ($unit['segler']['Deff']['ground']*($_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone'] ) + $unit['segler']['Deff']['air']*($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] ))/(($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] + $_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone'])>0?($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] + $_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone']):1)*($buildings["schild"][(Int)$_POST['shield']]["bonus"]/100+1);
	$deff['stosstruppe'] = ($unit['stosstruppe']['Deff']['ground']*($_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone'] ) + $unit['stosstruppe']['Deff']['air']*($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] ))/(($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] + $_POST['a_techniker'] + $_POST['a_abfangjaeger'] + $_POST['a_drone'])>0?($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] + $_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone']):1)*($buildings["schild"][(Int)$_POST['shield']]["bonus"]/100+1);
	$deff['drone'] = ($unit['drone']['Deff']['ground']*($_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone'] ) + $unit['drone']['Deff']['air']*($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] ))/(($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] + $_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone'])>0?($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] + $_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone']):1)*($buildings["schild"][(Int)$_POST['shield']]["bonus"]/100+1);
	$deff['kreuzer'] = ($unit['kreuzer']['Deff']['ground']*($_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone'] ) + $unit['kreuzer']['Deff']['air']*($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] ))/(($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] + $_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone'])>0?($_POST['a_abfangjaeger'] + $_POST['a_segler'] + $_POST['a_kreuzer'] + $_POST['a_stosstruppe'] + $_POST['a_techniker'] + $_POST['a_drone']):1)*($buildings["schild"][(Int)$_POST['shield']]["bonus"]/100+1);
	
	
	$verteidiger = $deff['abfangjaeger']*$_POST['v_abfangjaeger']+$deff['techniker']*$_POST['v_techniker']+$deff['segler']*$_POST['v_segler']+$deff['stosstruppe']*$_POST['v_stosstruppe']+$deff['drone']*$_POST['v_drone']+$deff['kreuzer']*$_POST['v_kreuzer']+$buildings["schild"][(Int)$_POST['shield']]["grundverteidigung"];
	$angreifer = $unit['abfangjaeger']['Att']*$_POST['a_abfangjaeger']+$unit['techniker']['Att']*$_POST['a_techniker']+$unit['segler']['Att']*$_POST['a_segler']+$unit['stosstruppe']['Att']*$_POST['a_stosstruppe']+$unit['drone']['Att']*$_POST['a_drone']+$unit['kreuzer']['Att']*$_POST['a_kreuzer'];
	
	
	$vk_abfangjaeger = 0;
	$vk_techniker = 0;
	$vk_segler = 0;
	$vk_stosstruppe = 0;
	$vk_drone = 0;
	$vk_kreuzer = 0;
	$ak_abfangjaeger = 0;
	$ak_techniker = 0;
	$ak_segler = 0;
	$ak_stosstruppe = 0;
	$ak_drone = 0;
	$ak_kreuzer = 0;
	if(((Int)$angreifer == 0) && ((Int)$verteidiger != 0)){
		$vk_abfangjaeger = (Int)$_POST['v_abfangjaeger'];
		$vk_techniker = (Int)$_POST['v_techniker'];
		$vk_segler = (Int)$_POST['v_segler'];
		$vk_stosstruppe = (Int)$_POST['v_stosstruppe'];
		$vk_drone = (Int)$_POST['v_drone'];
		$vk_kreuzer = (Int)$_POST['v_kreuzer'];
	}
	elseif($verteidiger > $angreifer){
		//echo $angreifer."<br>".$verteidiger."<br>".$buildings["schild"][(Int)$_POST['shield']]["grundverteidigung"]."<br>";
		$killedpoints = $angreifer-(($verteidiger-$angreifer)/50)-$buildings["schild"][(Int)$_POST['shield']]["grundverteidigung"];
		if($killedpoints < 0) $killedpoints = 0;
		if($_POST['v_abfangjaeger'] > 0){
			$Faktor_abfangjaeger = ($verteidiger-$buildings["schild"][(Int)$_POST['shield']]["grundverteidigung"])/($deff['abfangjaeger']*$_POST['v_abfangjaeger']);
			$vk_abfangjaeger = ($_POST['v_abfangjaeger']-$killedpoints/$Faktor_abfangjaeger/$deff['abfangjaeger']);
		}
		if($_POST['v_techniker'] > 0){
			$Faktor_techniker = ($verteidiger-$buildings["schild"][(Int)$_POST['shield']]["grundverteidigung"])/($deff['techniker']*$_POST['v_techniker']);
			$vk_techniker = ($_POST['v_techniker']-$killedpoints/$Faktor_techniker/$deff['techniker']);
		}
		if($_POST['v_segler'] > 0){
			$Faktor_segler = ($verteidiger-$buildings["schild"][(Int)$_POST['shield']]["grundverteidigung"])/($deff['segler']*$_POST['v_segler']);
			$vk_segler = ($_POST['v_segler']-$killedpoints/$Faktor_segler/$deff['segler']);
		}
		if($_POST['v_stosstruppe'] > 0){
			$Faktor_stosstruppe = ($verteidiger-$buildings["schild"][(Int)$_POST['shield']]["grundverteidigung"])/($deff['stosstruppe']*$_POST['v_stosstruppe']);
			$vk_stosstruppe = ($_POST['v_stosstruppe']-$killedpoints/$Faktor_stosstruppe/$deff['stosstruppe']);
		}
		if($_POST['v_drone'] > 0){
			$Faktor_drone = ($verteidiger-$buildings["schild"][(Int)$_POST['shield']]["grundverteidigung"])/($deff['drone']*$_POST['v_drone']);
			$vk_drone = ($_POST['v_drone']-$killedpoints/$Faktor_drone/$deff['drone']);
		}
		if($_POST['v_kreuzer'] > 0){
			$Faktor_kreuzer = ($verteidiger-$buildings["schild"][(Int)$_POST['shield']]["grundverteidigung"])/($deff['kreuzer']*$_POST['v_kreuzer']);
			$vk_kreuzer = ($_POST['v_kreuzer']-$killedpoints/$Faktor_kreuzer/$deff['kreuzer']);
		}
	}
	elseif($verteidiger < $angreifer){
		$killedpoints = $verteidiger-(($angreifer-$verteidiger)/50);
		if($_POST['a_abfangjaeger'] > 0){
			$Faktor_abfangjaeger = $angreifer/($deff['abfangjaeger']*$_POST['a_abfangjaeger']);
			$ak_abfangjaeger = ($_POST['a_abfangjaeger']-$killedpoints/$Faktor_abfangjaeger/$deff['abfangjaeger']);
		}
		if($_POST['a_techniker'] > 0){
			$Faktor_techniker = $angreifer/($deff['techniker']*$_POST['a_techniker']);
			$ak_techniker = ($_POST['a_techniker']-$killedpoints/$Faktor_techniker/$deff['techniker']);
		}
		if($_POST['a_segler'] > 0){
			$Faktor_segler = $angreifer/($deff['segler']*$_POST['a_segler']);
			$ak_segler = ($_POST['a_segler']-$killedpoints/$Faktor_segler/$deff['segler']);
		}
		if($_POST['a_stosstruppe'] > 0){
			$Faktor_stosstruppe = $angreifer/($deff['stosstruppe']*$_POST['a_stosstruppe']);
			$ak_stosstruppe = ($_POST['a_stosstruppe']-$killedpoints/$Faktor_stosstruppe/$deff['stosstruppe']);
		}
		if($_POST['a_drone'] > 0){
			$Faktor_drone = $angreifer/($deff['drone']*$_POST['a_drone']);
			$ak_drone = ($_POST['a_drone']-$killedpoints/$Faktor_drone/$deff['drone']);
		}
		if($_POST['a_kreuzer'] > 0){
			$Faktor_kreuzer = $angreifer/($deff['kreuzer']*$_POST['a_kreuzer']);
			$ak_kreuzer = ($_POST['a_kreuzer']-$killedpoints/$Faktor_kreuzer/$deff['kreuzer']);
		}
	}
	?>
      <br>
      <table border="1" cellpadding="1" cellspacing="1">
        <tbody>
          <tr>
            <td colspan="2" rowspan="1">Einheit</td>
            <td>Abfangj&auml;ger</td>
            <td>Techniker</td>
            <td>Luftschiff der Seglerklasse</td>
            <td>Sto&szlig;truppe</td>
            <td>Drone</td>
            <td>Luftschiff der Kreuzerklasse</td>
          </tr>
          <tr>
            <td colspan="1" rowspan="2">Angreifer</td>
			<td>Angreifend</td>
            <td><?=(Int)round($a_abfangjaeger)?></td>
            <td><?=(Int)round($a_techniker)?></td>
            <td><?=(Int)round($a_segler)?></td>
            <td><?=(Int)round($a_stosstruppe)?></td>
            <td><?=(Int)round($a_drone)?></td>
            <td><?=(Int)round($a_kreuzer)?></td>
          </tr>
		  <tr>
			<td>&Uuml;berlebend</td>
            <td><?=(Int)round($ak_abfangjaeger)?></td>
            <td><?=(Int)round($ak_techniker)?></td>
            <td><?=(Int)round($ak_segler)?></td>
            <td><?=(Int)round($ak_stosstruppe)?></td>
            <td><?=(Int)round($ak_drone)?></td>
            <td><?=(Int)round($ak_kreuzer)?></td>
          </tr>
          <tr>
            <td colspan="1" rowspan="2">Verteidiger</td>
			<td>Verteidigend</td>
            <td><?=(Int)round($v_abfangjaeger)?></td>
            <td><?=(Int)round($v_techniker)?></td>
            <td><?=(Int)round($v_segler)?></td>
            <td><?=(Int)round($v_stosstruppe)?></td>
            <td><?=(Int)round($v_drone)?></td>
            <td><?=(Int)round($v_kreuzer)?></td>
          </tr>
		   <tr>
			<td>&Uuml;berlebend</td>
            <td><?=(Int)round($vk_abfangjaeger)?></td>
            <td><?=(Int)round($vk_techniker)?></td>
            <td><?=(Int)round($vk_segler)?></td>
            <td><?=(Int)round($vk_stosstruppe)?></td>
            <td><?=(Int)round($vk_drone)?></td>
            <td><?=(Int)round($vk_kreuzer)?></td>
          </tr>
        </tbody>
      </table>

<?php } ?> <br>
      <form method="post" action="simu.php" name="Simulator">
        <table border="1" cellpadding="1" cellspacing="1">
          <tbody>
            <tr>
              <td>Einheit</td>
              <td>Angreifer</td>
              <td>Verteidiger</td>
            </tr>
            <tr>
              <td>Abfangj&auml;ger</td>
              <td>
              <input size="10" name="a_abfangjaeger" value="<?=$_POST['a_abfangjaeger']?>"></td>
              <td><input size="10" name="v_abfangjaeger" value="<?=$_POST['v_abfangjaeger']?>">
              </td>
            </tr>
            <tr>
              <td>Techniker</td>
              <td><input size="10" name="a_techniker" value="<?=$_POST['a_techniker']?>">
              </td>
              <td><input size="10" name="v_techniker" value="<?=$_POST['v_techniker']?>">
              </td>
            </tr>
            <tr>
              <td>Luftschiff der Seglerklasse</td>
              <td><input size="10" name="a_segler" value="<?=$_POST['a_segler']?>">
              </td>
              <td><input size="10" name="v_segler" value="<?=$_POST['v_segler']?>">
              </td>
            </tr>
            <tr>
              <td>Sto&szlig;truppe</td>
              <td><input size="10" name="a_stosstruppe" value="<?=$_POST['a_stosstruppe']?>">
              </td>
              <td><input size="10" name="v_stosstruppe" value="<?=$_POST['v_stosstruppe']?>">
              </td>
            </tr>
            <tr>
              <td>Drone</td>
              <td><input size="10" name="a_drone" value="<?=$_POST['a_drone']?>">
              </td>
              <td><input size="10" name="v_drone" value="<?=$_POST['v_drone']?>">
              </td>
            </tr>
            <tr>
              <td>Luftschiff der Kreuzerklasse</td>
              <td><input size="10" name="a_kreuzer" value="<?=$_POST['a_kreuzer']?>">
              </td>
              <td><input size="10" name="v_kreuzer" value="<?=$_POST['v_kreuzer']?>">
              </td>
            </tr>
            <tr>
              <td>Schutzschild</td>
              <td>
              </td>
              <td><input size="10" name="shield" value="<?=$_POST['shield']?>">
              </td>
            </tr>
          </tbody>
        </table>
        <input name="Send" value="Berechnen" type="submit"><br>
      </form>
<?php DrawFooter(); ?>
