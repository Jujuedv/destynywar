<?php include_once("data/funcs.php"); DrawHeader("Allianz - Verlassen"); ?>
<?php if($_SESSION['allianz'] == NULL){ ?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php }else{ 

if(is_numeric($_GET['id']) && $_SESSION['allianz_data']['gruender']){
	$userallianzu = SqlObjQuery("SELECT * FROM `allianz_user` WHERE userid='{$_GET['id']}' and allianzid='{$_SESSION['allianz']}'");
	$gruenderzahl = SqlObjQuery("SELECT count(*) as cnt FROM `allianz_user` WHERE gruender=1 and allianzid='{$_SESSION['allianz']}'");
	if(!$userallianzu->gruender || ($userallianzu->gruender && $gruenderzahl->cnt > 1)){
		mysql_query("DELETE FROM `allianz_user` WHERE userid='{$_GET['id']}'");
	}
}
elseif(is_numeric($_GET['id']) && $_SESSION['allianz_data']['fuehrung']){
	$userallianzu = SqlObjQuery("SELECT * FROM `allianz_user` WHERE userid='{$_GET['id']}' and allianzid='{$_SESSION['allianz']}'");
	if(!$userallianzu->gruender){
		mysql_query("DELETE FROM `allianz_user` WHERE userid='{$_GET['id']}'");
	}
}
?>
<script type="text/javascript">window.location.href = "allianz_user.php";</script>
<?php
}DrawFooter(); ?>