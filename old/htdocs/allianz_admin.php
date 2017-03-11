<?php include_once("data/funcs.php"); DrawHeader("Allianz - Forum"); ?>
<?php if($_SESSION['allianz'] == NULL || !$_SESSION['allianz_data']['gruender']){ ?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php }else{ 
DrawAllianzTable();
$error = array();
$allianz_datas = SqlObjQuery("SELECT `allianzname` , `allianztext` FROM `allianzen` WHERE `id` = {$_SESSION['allianz']}");
if(isset($_GET['change']) && !empty($_POST['name'])){
	$allianzname = htmlentities($_POST['name'],ENT_QUOTES);
	$allianztext = htmlentities($_POST['description'],ENT_QUOTES);
	$sql = "SELECT * FROM allianzen WHERE allianzname='{$allianzname}' and id<>{$_SESSION['allianz']}";
	$erg = mysql_query($sql);
	if(($out = mysql_fetch_object($erg)) == false){
		mysql_query("UPDATE `allianzen` SET `allianzname` = '{$allianzname}', `allianztext` = '{$allianztext}' WHERE `allianzen`.`id` = {$_SESSION['allianz']}");
	}
	else $error['name'] = "Allianzname schon vergeben.";
	$allianz_datas = SqlObjQuery("SELECT `allianzname` , `allianztext` FROM `allianzen` WHERE `id` = {$_SESSION['allianz']}");
}
?>
<br/>
<div id="change" name="change">
	<form id="new_forum" name="new_forum" method="POST" action="allianz_admin.php?change">
		<table>
			<tr><td valign="top">Name:</td><td valign="top"><input type="text" id="name" name="name" size="50" value="<?=$allianz_datas->allianzname?>"/></td><td valign="top"><?=$error['name']?></td></tr>
			<tr><td valign="top" colspan="2"><?php BBCodesLeiste('new_forum','description'); ?></td></tr>
			<tr><td valign="top">Beschreibung:</td><td valign="top"><textarea id="description" name="description" rows="7" cols="50"><?=$allianz_datas->allianztext?></textarea></td><td valign="top"><?=$error['desription']?></td></tr>
		</table>
		<input type="submit" name="Send" value="Daten &auml;ndern"/>
	</form>
</div>
<?php
}DrawFooter(); ?>