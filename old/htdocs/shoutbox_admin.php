<?php include_once("data/funcs.php"); DrawHeader("Allianz - Shoutboxen Administrieren"); ?>
<?php if($_SESSION['allianz'] == NULL || !$_SESSION['allianz_data']['shoutbox']){ ?>
<script type="text/javascript">window.location.href = "allianz.php";</script>
<?php }else{
DrawAllianzTable();
?>
<?php 
if(isset($_GET['add']) && ( strlen($_POST['title']) >= 3 ) ){
	mysql_query("INSERT INTO `shoutboxen` ( `allianz` , `name` ) VALUES ( {$_SESSION['allianz']}, '".htmlentities($_POST['title'],ENT_QUOTES)."');");
}
if(isset($_GET['delete']) && is_numeric($_GET['id'])){
	mysql_query("DELETE FROM shoutboxen WHERE allianz={$_SESSION['allianz']} and id={$_GET['id']}");
}
if(isset($_GET['change']) && is_numeric($_GET['id'])){
	if(SqlObjQuery("SELECT * FROM shoutboxen WHERE allianz={$_SESSION['allianz']} and id={$_GET['id']}")){
		if(isset($_GET['remove']) && is_numeric($_GET['user'])){
			mysql_query("DELETE FROM shoutbox_user WHERE shoutbox={$_GET['id']} and user={$_GET['user']}");
		}
		if(isset($_GET['adduser']) && GetUserId($_POST['user'])){
			mysql_query("DELETE FROM shoutbox_user WHERE shoutbox={$_GET['id']} and user=".GetUserId($_POST['user'])."");
			mysql_query("INSERT INTO `shoutbox_user` ( `shoutbox` , `user` ) VALUES ( {$_GET['id']}, ".GetUserId($_POST['user'])." );");
		}
		if(isset($_GET['clear'])){
			mysql_query("DELETE FROM shoutbox_nachrichten WHERE shoutbox={$_GET['id']}");
		}
		$erg = mysql_query("SELECT * FROM shoutbox_user WHERE shoutbox={$_GET['id']}");
		?>
		<br/>
		<table border='1' cellspacing="3">
		<thead><tr><th>User</th><th>entfernen</th></tr></thead>
		<?php
		while($out = mysql_fetch_object($erg)){
			?>
			<tr><td><?=GetUserlink($out->user)?></td><td><a href="shoutbox_admin.php?change&user=<?=$out->id?>&id=<?=$_GET['id']?>&remove">entfernen</a></td></tr>
			<?php
		}
		?>
		</table><br/>
		<form action="shoutbox_admin.php?change&id=<?=$_GET['id']?>&adduser" method="POST">Neuen User hinzuf&uuml;gen: <input name="user" id="user" type="text"/><input type="submit" value="Hinzuf&uuml;gen" /></form>
		<br/>
		<a href="shoutbox_admin.php?change&id=<?=$_GET['id']?>&clear">Shoutbox leeren</a><br/><br/>
		<?php
	}
}


$erg = mysql_query("SELECT * FROM shoutboxen WHERE allianz={$_SESSION['allianz']}");
?><br/>
<table border='1' cellspacing="3">
<thead><tr><th>Shoutbox</th><th>verwalten</th><th>l&ouml;schen</th></tr></thead>
<?php
while($out = mysql_fetch_object($erg)){
	?>
	<tr><td><?=$out->name?></td><td><a href="shoutbox_admin.php?change&id=<?=$out->id?>">verwalten</a></td><td><a href="shoutbox_admin.php?delete&id=<?=$out->id?>">l&ouml;schen</a></td></tr>
	<?php
}
?>
</table><br/>
<form action="shoutbox_admin.php?add" method="POST">Neue Shoutbox hinzuf&uuml;gen: <input name="title" id="title" type="text"/><input type="submit" value="Erstellen" /></form>
<?php 
?>
<?php
}DrawFooter(); ?>