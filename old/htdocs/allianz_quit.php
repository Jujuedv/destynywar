<?php include_once("data/funcs.php"); DrawHeader("Allianz - Verlassen"); ?>
<?php if($_SESSION['allianz'] == NULL){ ?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php }else{ 


$userallianzu = SqlObjQuery("SELECT * FROM `allianz_user` WHERE userid='{$_SESSION['userid']}'");
$gruenderzahl = SqlObjQuery("SELECT count(*) as cnt FROM `allianz_user` WHERE gruender=1 and allianzid='{$_SESSION['allianz']}'");
if(!$userallianzu->gruender || ($userallianzu->gruender && $gruenderzahl->cnt > 1)){
	mysql_query("DELETE FROM `allianz_user` WHERE userid='{$_SESSION['userid']}'");
}
else{}
?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php
}DrawFooter(); ?>