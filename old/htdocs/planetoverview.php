<?php
	include_once("data/funcs.php");
	DrawHeader("Übersicht");
	$breaks = 0;
	if(!empty($_POST['pname']))	mysql_query("UPDATE `planets` SET `planetname` = '".htmlentities($_POST['pname'],ENT_QUOTES)."' WHERE `id` = {$_SESSION['planetid']}");
	$out = SqlObjQuery("SELECT * FROM `planets` WHERE `id` = {$_SESSION['planetid']};");
	echo "<h1><a href='changeplanetname.php'>".$out->planetname."</a></h1>";
?>
</center>
<table>
	<tr>
		<td valign="top">
			<h1>Produktion - Rohstoffe</h1>
			<table>
				<tr><td>Platin:</td><td><?=$buildings["produktion"][$buildings["miene"]["Stufe"]]*$settings['speed']." - ".$ress["Platin"]?></td></tr>
				<tr><td>Stahl:</td><td><?=$buildings["produktion"][$buildings["schmelze"]["Stufe"]]*$settings['speed']." - ".$ress["Stahl"]?></td></tr>
				<tr><td>Energie:</td><td><?=floor($buildings["produktion"][$buildings["reaktor"]["Stufe"]]*(100-$buildings["reaktor"]["verhaeltnis"])/50)*$settings['speed']." - ".$ress["Energie"]?></td></tr>
				<tr><td>Plasma:</td><td><?=floor($buildings["produktion"][$buildings["reaktor"]["Stufe"]]*($buildings["reaktor"]["verhaeltnis"])/50)*$settings['speed']." - ".$ress["Plasma"]?></td></tr>
				<tr><td>Plasmid:</td><td><?=$buildings["produktion"][$buildings["biolabor"]["Stufe"]]*$settings['speed']." - ".$ress["Plasmid"]?></td></tr>
				<tr><td>Nahrung:</td><td><?=$buildings["produktion"][$buildings["bauer"]["Stufe"]]*$settings['speed']." - ".$ress["Nahrung"]?></td></tr>
				<tr><td>Freie Bev&ouml;lkerung:</td><td><?=($buildings["versorgtePlaetze"][$out->bauer]-$out->troopsGes)?></td></tr>
			</table>
			<br/>
		</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td valign="top">
			<h1>Geb&auml;ude</h1>
			<table>
				<?php
					foreach($builds as $build => $cont){ if($cont["Stufe"] != 0){?>
				<tr><td><a href="<?=$build?>.php"><?=htmlentities($cont["Name"],ENT_QUOTES)?></a></td><td>(Stufe: <?=$cont["Stufe"]?>)</td></tr>
				<?php }}
				
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<h1>Truppen</h1>
			<table>
				<?php
					$unums = SqlArrQuery("SELECT * FROM `planets` WHERE id = {$_SESSION['planetid']}");
					foreach($units as $unit => $unitdata)
					{
						if(!$unums[$unit]) continue;
				?>
				<tr>
					<td>
						<?=$unitdata['Name']?>
					</td>
					<td>
						:
					</td>
					<td>
						<?=$unums[$unit]?>
					</td>
				</tr>
				<?php
					}
				?>
			</table>
		</td>
	</tr>
</table>
<center>
<?php DrawFooter(); ?>