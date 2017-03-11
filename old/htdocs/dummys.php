<?php
	include_once("data/funcs.php");
	DrawHeader("Dummys"); 
	for($i = 0;$i < 10000 ;$i++)CreatePlanet();entfernen für debuggen
?>
<h1>Dummys erstellt!</h1><br/>
<?php DrawFooter(); ?>