<?php include_once("data/funcs.php"); DrawHeader("Allianz"); ?>
<?php 
	if($_SESSION['allianz'] == NULL){
		$sql = "SELECT * FROM allianzen_einladungen WHERE userid='{$_SESSION['userid']}' and id='{$_GET['id']}'";
		$erg = mysql_query($sql);
		if($out = mysql_fetch_object($erg)){
			mysql_query("DELETE FROM allianzen_einladungen WHERE userid='{$_SESSION['userid']}' and id='{$_GET['id']}'");
		}
	}
?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php DrawFooter(); ?>