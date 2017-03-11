<?php
include_once("data/funcs.php");
drawheader("Hilfe");
?>
<h3>Truppenwerte:</h3>
<table border="1">
<tr><td><b>Truppe</b></td><td><b>Angriff</b></td><td><b>Bodenverteidigung</b></td><td><b>Luftverteidigung</b></td></tr>
<?php foreach($units as $unit => $cont){ ?>
<tr><td><?=$cont['Name']?></td><td><?=$cont['Att']?></td><td><?=$cont['Deff']['ground']?></td><td><?=$cont['Deff']['air']?></td></tr>
<?php } ?>
</table><br/><br/>
<h3>Produktion:</h3>
<table border="1">
<tr><td><b>Stufe</b></td><td><b>Produktion</b></td></tr>
<?php foreach($buildings["produktion"] as $stufe => $cont){ if($stufe == 26)continue;?>
<tr><td><?=$stufe?></td><td><?=$cont?></td></tr>
<?php } ?>
</table>

<h3>Laufzeitrechner:</h3>
<form method="POST" action="help.php?runtime">
Planet 1:<input id="p1" name="p1"/><br/>
Planet 2:<input id="p2" name="p2"/><br/>
<input type="submit"/>
</form><br/><br/>
<?php
if(isset($_GET['runtime'])){
	$dist = GetDistance($_POST['p1'],$_POST['p2']);
?>
<table border="1">
<tr><td><b>Truppe</b></td><td><b>Zeit</b></td></tr>
<?php foreach($units as $unit => $cont){ ?>
<tr><td><?=$cont['Name']?></td><td><?=time_duration(($dist*$cont['speed']*60)/$settings['attackspeed'])?></td></tr>
<?php } ?>
</table>
<?php
}
?>
<?php drawfooter(); ?>