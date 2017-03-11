<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html template="true">
<head>
  <title>Destyny War - Admin</title>
  <style type="text/css">
body { 
	background-image: url(../../pics/normbg.jpg);
	background-repeat:no-repeat;
	background-attachment:fixed;
	font-family: Courier New;
	background-color: rgb(0, 0, 0);
	color: rgb(255, 0, 0);
}

a:link { color:#FF0000; }
a:visited { color:#FF0000; }
a:active { color:#FF0000; }

.kasten { 
	border: 3px inset rgb(0, 0, 0);
}
.brett { 
	border: 3px outset rgb(0, 0, 0);
}
  </style>
</head>
<body style="background-color: rgb(0, 0, 0); color: rgb(255, 0, 0);">
<?php
	session_start();
	if(!$_SESSION['admin_login']){?>
<script type="text/javascript">
	window.location.href = "..";
</script>
	<?php
	}
	else{
		?>
<table>
	<tr>
		<td valign="top" style="width:350px;">
		<ul>
		<?php
		foreach (glob("*.php") as $filename) {
			$ohnePHP = explode( "." , $filename , -1 );
			echo "<li><a href='$filename'>".$ohnePHP[0]."</a></li>
";
		}
		mysql_connect("localhost", "root","jgames") or die ("Fehler: 1");
		mysql_select_db("dw") or die ("Fehler: 2");
		?>
		</ul>
		</td>
		<td valign="top">
		<center>
		

<?php
function getUserName($userid){
	$sql = "SELECT username FROM user WHERE id = '{$userid}'";
	$erg = mysql_query($sql);
	if($out = mysql_fetch_object($erg)){
		mysql_free_result($erg);
		return $out->username;
	}
	mysql_free_result($erg);
	return 0;
}
function getAllianzName($allianzid){
	$sql = "SELECT `allianzname` FROM `allianzen` WHERE id = '{$allianzid}'";
	if($out = SqlObjQuery($sql)){
		return $out->allianzname;
	}
	return 0;
}
function SqlObjQuery($sql_query){
	$erg = mysql_query($sql_query);
	if(!$erg) die("Falscher Query: ".$sql_query);
	$out = mysql_fetch_object($erg);
	mysql_free_result($erg);
	return $out;
}
?>
<br/>
<?php
$erg = mysql_query("SELECT * FROM shoutboxen GROUP BY id");
while($out = mysql_fetch_object($erg)){
?>
	<a href="shoutbox.php?id=<?=$out->id?>"><?=getAllianzName($out->allianz)?>|---|<?=$out->name?></a>&nbsp;&nbsp;
<?php
}
?>
<br/>
<br/>
<?php
if(is_numeric($_GET['id'])){
	if(!is_numeric($_GET['read'])) $_GET['read'] = 50;
	if(SqlObjQuery("SELECT * FROM shoutbox_user WHERE shoutbox={$_GET['id']}")){
?>
<div><form action="shoutbox.php?id=<?=$_GET['id']?>&send" method="POST"><input id="sender" name="sender" type="text"/><input type="submit" value="Nachricht senden"/></form></div>
<div name="output"><table border="0" cellspacing="3">
<?php
		if(isset($_GET['send'])){
			mysql_query("INSERT INTO `shoutbox_nachrichten` ( `user` , `message` , `shoutbox` ) VALUES ( {$_SESSION['userid']}, '".htmlentities($_POST['sender'],ENT_QUOTES)."', {$_GET['id']});");
		}
		$erg = mysql_query("SELECT * FROM `shoutbox_nachrichten` WHERE `shoutbox` ={$_GET['id']} ORDER BY `id` DESC LIMIT 0, {$_GET['read']}");
		while($out = mysql_fetch_object($erg)){
			?>
			<tr><td align="top" style="background-color:#999;"><?=GetUserName($out->user)?></td><td align="top" style="background-color:#999;"><?=$out->message?></td></tr> 
			<?php
		}
	}
}
?>
</table></div>
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
			req.open("GET", '<?=$worldroot?>/admin/actions/shoutbox.php?id=<?=$_GET['id']?>&getdata&read=<?=$_GET['read']?>', true);
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
<?php } ?>
</center>
		</td>
	</tr>
</table>
		<?php
	}
?>
</body>
</html>