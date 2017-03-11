<?php include_once("data/funcs.php"); DrawHeader("Allianz - Shoutbox"); ?>
<?php

mysql_query("DELETE FROM `shoutboxen` WHERE `allianz` NOT IN (SELECT `id` FROM `allianzen`) and `allianz` <> -1;");
mysql_query("DELETE FROM `shoutbox_user` WHERE `user` NOT IN (SELECT `id` FROM `user`) or `shoutbox` NOT IN (SELECT `id` FROM `shoutboxen`);");


$erg = mysql_query("SELECT * FROM shoutbox_user WHERE user={$_SESSION['userid']}");
while($out = mysql_fetch_object($erg)){
	$out2 = SqlObjQuery("SELECT * FROM shoutboxen WHERE id={$out->shoutbox}");
?>
	<a href="shoutbox.php?id=<?=$out2->id?>"><?=$out2->name?></a>&nbsp;&nbsp;
<?php
}
?>
<br/>
<br/>
<?php
if(is_numeric($_GET['id'])){
	if(SqlObjQuery("SELECT * FROM shoutbox_user WHERE user={$_SESSION['userid']} and shoutbox={$_GET['id']}")){
?>
<div><form action="shoutbox.php?id=<?=$_GET['id']?>&send" method="POST"><input id="sender" name="sender" type="text"/><input type="submit" value="Nachricht senden"/></form></div>
<div name="output"><table border="0" cellspacing="3">
<?php
		mysql_query("UPDATE user SET shoutbox = {$_GET['id']} WHERE id = {$_SESSION['userid']}");
		if(isset($_GET['send']) && !empty($_POST['sender'])){
			mysql_query("INSERT INTO `shoutbox_nachrichten` ( `user` , `message` , `shoutbox` ) VALUES ( {$_SESSION['userid']}, '".htmlentities($_POST['sender'],ENT_QUOTES)."', {$_GET['id']});");
		}
		$erg = mysql_query("SELECT * FROM `shoutbox_nachrichten` WHERE `shoutbox` ={$_GET['id']} ORDER BY `id` DESC LIMIT 0, 10");
		while($out = mysql_fetch_object($erg)){
			?>
			<tr><td align="top" style="background-color:#444;"><?=GetUserLink($out->user)?></td><td align="top" style="background-color:#333;"><?=$out->message?></td></tr> 
			<?php
		}
		?>
</table></div>
		<?php
	}
}
?>
<?php
if(!isset($_GET['getdata'])){
?>
<div style="visibility:hidden" id="saver"></div>
<script type="text/javascript">
	
	function send(){
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
					alert("in");
				}
			}  
		}
		if(req){
			req.open("GET", '<?=$worldroot?>shoutbox.php?id=<?=$_GET['id']?>&getdata', true);
			req.onreadystatechange = function(){ 
				switch(req.readyState) {
				case 4:
					if(req.status!=200) {
						alert("Fehler:"+req.status); 
					}else{
						var html = req.responseText;
						document.getElementById('saver').innerHTML = html;
						document.getElementsByName('output')[0].innerHTML = document.getElementsByName('output')[1].innerHTML;
					}
					break;
				default:
					return false;
				break;     
				}
			}
			req.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			req.send();
		}else alert("Kann nicht automatisch nachladen!");
	}
	send();
	window.setInterval("send();",5000);
</script>
<?php
}DrawFooter(); ?>