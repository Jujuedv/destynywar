<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
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
function SqlObjQuery($sql_query){
	$erg = mysql_query($sql_query);
	if(!$erg) die("Falscher Query: ".$sql_query);
	$out = mysql_fetch_object($erg);
	mysql_free_result($erg);
	return $out;
}
function SqlArrQuery($sql_query){
	$erg = mysql_query($sql_query);
	if(!$erg) die("Falscher Query: ".$sql_query);
	$out = mysql_fetch_array($erg);
	mysql_free_result($erg);
	return $out;
}
function getUserId($username){
	$sql = "SELECT id FROM user WHERE username = '".htmlentities($username,ENT_QUOTES)."' and userpasswd<>''";
	$erg = mysql_query($sql);
	if($out = mysql_fetch_object($erg)){
		mysql_free_result($erg);
		return $out->id;
	}
	mysql_free_result($erg);
	return 0;
}
function getUserAllianz($userid){
	$sql = "SELECT allianzid FROM allianz_user WHERE userid = '{$userid}'";
	$erg = mysql_query($sql);
	if($out = mysql_fetch_object($erg)){
		mysql_free_result($erg);
		return $out->allianzid;
	}
	mysql_free_result($erg);
	return 0;
}
function getAllianzId($allianzname){
	$sql = "SELECT `id` FROM `allianzen` WHERE allianzname = '".htmlentities($allianzname,ENT_QUOTES)."'";
	if($out = SqlObjQuery($sql)){
		return $out->id;
	}
	return 0;
}
function getAllianzName($allianzid){
	$sql = "SELECT `allianzname` FROM `allianzen` WHERE id = '{$allianzid}'";
	if($out = SqlObjQuery($sql)){
		return $out->allianzname;
	}
	return 0;
}
function getUserPlatz($userid){
	$sql = "SELECT platz FROM user_platz WHERE id = '{$userid}'";
	$erg = mysql_query($sql);
	if($out = mysql_fetch_object($erg)){
		mysql_free_result($erg);
		return $out->platz;
	}
	mysql_free_result($erg);
	return 0;
}
function getUserPoints($userid){
	$sql = "SELECT points FROM user WHERE id = '{$userid}'";
	if($out = SqlObjQuery($sql)){
		return $out->points;
	}
	return 0;
}
function getAllianzPoints($allianzid){
	$sql = "SELECT SUM(points) as points FROM `allianz_user`,user WHERE `allianzid` = {$allianzid} and user.id = userid";
	if($out = SqlObjQuery($sql)){
		return $out->points;
	}
	return 0;
}
function getUserLink($userid){
	if($username = getUserName($userid)) return "<a href=\"player.php?id={$userid}\">{$username}</a>";
	else echo '---';
}
function getAllianzLink($allianzid){
	if($allianzname = getAllianzName($allianzid)) return "<a href=\"allianz_data.php?id={$allianzid}\">{$allianzname}</a>";
	else echo '---';
}
function getPlanetLink($planetid){
	$sql = "SELECT * FROM planets WHERE id = '{$planetid}'";
	if($out = SqlObjQuery($sql)) return "<a href=\"planet.php?id={$planetid}\">{$out->planetname} ({$out->xcoords}|{$out->ycoords})</a>";
	return "---";
}
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
function BBcodes($string){
	while($old_string != $string)
	{
		$old_string = $string;
		$string = preg_replace_callback('{\[(\w+)((=)(.+)|())\]((.|\n)*)\[/\1\]}U', 'bbcode_callback', $string);
	}
	$string = preg_replace('~•~','[',$string);
	return $string;
}
function bbcode_callback($matches){
	$tag = trim($matches[1]);
	$inner_string = $matches[6];
	$argument = $matches[4];
	switch($tag)
	{
		case 'b':
		case 'i':
		case 'u':
		case 'tr':
		case 'td':
			$replacement = "<$tag>$inner_string</$tag>";
			break;
		case 'code':
			$inner_string = preg_replace('{\[}','•',$inner_string);
			$replacement =  '<pre style="font-size:12px;width:450px;">' . $inner_string . '</pre>';
			break;
		case 'color':
				$color = preg_match("[^[0-9a-fA-F]{3,6}$]", $argument) ? '#' . $argument : $argument;
				$replacement =  '<span style="color:' . $color . '">' . $inner_string . '</span>';
			break;
		case 'email':
			$address = $argument ? $argument : $inner_string;
			$replacement =  '<a href="mailto:' . $address . '">' . $inner_string . '</a>';
			break;
		case 'img':
		if(preg_match('~admins/~i',$inner_string) == 0) $replacement =  '<img src="' . $inner_string . '" />';
			else if($admin) $replacement =  '<img src="' . $inner_string . '" />'; else $replacement='';
			break;
		case 'size':
			if (is_numeric($argument) && $argument > 5 && $argument < 64)
			{
				$replacement =  '<span style="font-size:' . $argument . 'px;">' . $inner_string . '</span>';
			}
				break;
		case 'quote':
			$replacement =  '<b>Zitat'.(($argument)?" von ".(GetUserLink(GetUserId($argument))):"").':</b><div style="background-color:#777;">' . $inner_string . '<div>';
			break;
		case 'url':
		case 'link':
			$url = $argument ? $argument : $inner_string;
			$replacement =  '<a onclick="if(confirm(\'Wir übenehmen keine \n Haftung für externe Seiten!!!\n\nWirklich besuchen?\')){return false;}" href="' . $url . '" target="_blank">' . $inner_string . '</a>';
			break;
		case 'a':
			$url = $argument ? $argument : $inner_string;
			$replacement =  '<a href="' . $url . '" target="_blank">' . $inner_string . '</a>';
			break;
		case 'spoiler':
			$replacement =  '<div id="spoiler" ><input type="button" onclick="spoiler(this);" value="aufklappen"/><div id="spoiler" style="background-color:#777;" ><span style="display:none;background-color:#999;" >' . $inner_string . '</span></div></div>';
			break;
		case 'planet':
			$zeichenkette = $inner_string;
			$suchmuster = '/([-\\+]?[0123456789]+)|([-\\+]?[0123456789]+)/';
			preg_match_all($suchmuster, $zeichenkette, $treffer);
			$out = SqlobjQuery("SELECT `id`
FROM `planets` WHERE `xcoords` = {$treffer[0][0]} AND `ycoords` = {$treffer[0][1]}");
			$pid = $out->id;
			$replacement = getPlanetLink($pid);
			break;
		/*case 'sound':
			$url = $argument ? $argument : $inner_string;
			$replacement =  '<h4>'. $inner_string .':</h4><embed src="' . $url . '" width="145" height="17" autostart="false" loop="false" border="0">';
			break;*/
		/*case 'video':
			$url = $argument ? $argument : $inner_string;
			
			if(preg_match("~v=(.+)~",$url,$erg)){
				// $replacement =  '<h4>'. $inner_string .':</h4><embed src="' . $url . '" width="145" height="17" autostart="false" loop="false" border="0">';
				$replacement =  '<object width="320" height="265"><param name="movie" value="http://www.youtube-nocookie.com/v/'.$erg[1].'&hl=de_DE&fs=1&rel=0&color1=0x3a3a3a&color2=0x999999"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/'.$erg[1].'&hl=de_DE&fs=1&rel=0&color1=0x3a3a3a&color2=0x999999" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="320" height="265"></embed></object>';//<object width="320" height="265"><param name="movie" value="http://www.youtube-nocookie.com/v/wd94wL9PBbA&hl=de_DE&fs=1&rel=0&color1=0x3a3a3a&color2=0x999999"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/wd94wL9PBbA&hl=de_DE&fs=1&rel=0&color1=0x3a3a3a&color2=0x999999" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="320" height="265"></embed></object>
			}
			else $replacement = "";
			break;*/
		case 'table':
			$replacement =  '<table border="1" cellpadding="2" cellspacing="2"><tbody>' . $inner_string . '</tbody></table>';
			break;
		case 'allianz':
			$allianzid = is_numeric($argument) ? $argument : GetAllianzId(html_entity_decode($inner_string,ENT_QUOTES));
			$replacement =  GetAllianzLink($allianzid);
			break;
		case 'player':
			$userid = is_numeric($argument) ? $argument : getUserId(html_entity_decode($inner_string,ENT_QUOTES));
			$replacement =  GetUserLink($userid);
			break;
		default:    // unknown tag => reconstruct and return original expression
			$replacement = '[' . $tag . ']' . $inner_string . '[/' . $tag .']';
			break;
	}
	return $replacement;
}		
		

		if(isset($_GET['send']) && (strlen($_POST['betreff']) > 2) && (strlen($_POST['inhalt']) > 2)){
			$erg = mysql_query("SELECT * FROM user WHERE userpasswd<>''");
			while($out = mysql_fetch_object($erg)){
				mysql_query("INSERT INTO `holopost_betreffs` ( `betreff` , `from_id` , `to_id` , `lastedit` ) VALUES ('".htmlentities($_POST['betreff'],ENT_QUOTES)."', '{$_SESSION['userid']}', '".$out->id."', '".time()."');");
				mysql_query("INSERT INTO `holopost_nachrichten` ( `userid` , `holopostid` , `message` , `uhrzeit` ) VALUES ( '{$_SESSION['userid']}', (SELECT MAX(`id`) FROM `holopost_betreffs` WHERE `from_id` = '{$_SESSION['userid']}'), '".BBCodes(nl2br(htmlentities($_POST['inhalt'],ENT_QUOTES)))."', '".date('d.m.y \\u\\m H:i:s')."');");
			}
		}
		?>
		<form method="POST" action="<?=$_SERVER['PHP_SELF']?>?send"><input type="text" id="betreff" name="betreff" size="50"/><br/><textarea id="inhalt" name="inhalt" rows="10" cols="50"></textarea><br/><input type="submit" name="Send" value="Abschicken"/></form>
</center>
		</td>
	</tr>
</table>
		<?php
	}
?>
</body>
</html>