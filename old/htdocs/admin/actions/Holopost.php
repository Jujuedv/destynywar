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
	if($out = SqlObjQuery($sql)) return "<a href=\"../../planet.php?id={$planetid}\">{$out->planetname} ({$out->xcoords}|{$out->ycoords})</a>";
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
function BBCodesLeiste($form,$feld){
?>
<table>
	<tr>
		<td><a href="javascript:insert('[b]','[/b]'			,'<?=$form?>','<?=$feld?>');"><img src="../../pics/BBCodes/Fett.jpg" height="16" width="16" alt="[b]" title="Fett"/></a></td>
		<td><a href="javascript:insert('[i]','[/i]'			,'<?=$form?>','<?=$feld?>');"><img src="../../pics/BBCodes/Kusiv.jpg" height="16" width="16" alt="[i]" title="Kusiv"/></a></td>
		<td><a href="javascript:insert('[u]','[/u]'			,'<?=$form?>','<?=$feld?>');"><img src="../../pics/BBCodes/Unterstrichen.jpg" height="16" width="16" alt="[u]" title="Unterstrichen"/></a></td>
		<td><a href="javascript:insert('[url]','[/url]'		,'<?=$form?>','<?=$feld?>');"><img src="../../pics/BBCodes/Url.jpg" alt="[url]" height="16" width="16" title="Link einf&uuml;gen"/></a></td>
		<td><a href="javascript:insert('[color]','[/color]'	,'<?=$form?>','<?=$feld?>');"><img src="../../pics/BBCodes/Farbe.jpg" alt="[color]" height="16" width="16" title="Schriftfarbe &auml;dern"/></a></td>
		<td><a href="javascript:insert('[size]','[/size]'		,'<?=$form?>','<?=$feld?>');"><img src="../../pics/BBCodes/Groesse.jpg" alt="[size]" height="16" width="16" title="Schriftgr&ouml;&szlig;e &auml;dern"/></a></td>
		<td><a href="javascript:insert('[quote]','[/quote]'	,'<?=$form?>','<?=$feld?>');"><img src="../../pics/BBCodes/Zitat.jpg" height="16" width="16" alt="[quote]" title="Zitat"/></a></td>
		<td><a href="javascript:insert('[spoiler]','[/spoiler]','<?=$form?>','<?=$feld?>');"><img src="../../pics/BBCodes/Spoiler.jpg" height="16" width="16" alt="[spoiler]" title="Spoiler"/></a></td>
		<td><a href="javascript:insert('[img]','[/img]'		,'<?=$form?>','<?=$feld?>');"><img src="../../pics/BBCodes/Bild.jpg" height="16" width="16" alt="[img]" title="Bild"/></a></td>
		<td><a href="javascript:insert('[player]','[/player]'	,'<?=$form?>','<?=$feld?>');"><img src="../../pics/BBCodes/Player.jpg" height="16" width="16" alt="[player]" title="Spieler einf&uuml;gen"/></a></td>
		<td><a href="javascript:insert('[planet]','[/planet]'	,'<?=$form?>','<?=$feld?>');"><img src="../../pics/BBCodes/Planet.jpg" height="16" width="16" alt="[planet]" title="Planet - Verwendung: [planet](x|y)[/planet]"/></a></td>
	</tr>
</table>

<?php
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
			$url = $argument ? $argument : $inner_string;
			$replacement =  '<a onclick="if(confirm(\'Wir übenehmen keine \n Haftung für externe Seiten!!!\n\nWirklich besuchen?\')){return false;}" href="' . $url . '" target="_blank">' . $inner_string . '</a>';
			break;
		case 'spoiler':
			$replacement =  '<div id="spoiler" ><input type="button" onclick="spoiler(this);" value="aufklappen"/><div id="spoiler" style="background-color:#777;" ><span style="display:none;background-color:#999;" >' . $inner_string . '</span></div></div>';
			break;
		case 'planet':
			$zeichenkette = $inner_string;
			$suchmuster = '/([-\\+]?[0123456789]+)|([-+]?[0123456789]\\+)/';
			preg_match($suchmuster, $zeichenkette, $treffer);
			$out = SqlobjQuery("SELECT `id`
FROM `planets` WHERE `xcoords` = {$treffer[0]} AND `ycoords` = {$treffer[1]}");
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
			$allianzid = is_numeric($argument) ? $argument : GetAllianzId($inner_string);
			$replacement =  GetAllianzLink($allianzid);
			break;
		case 'player':
			$userid = is_numeric($argument) ? $argument : getUserId($inner_string);
			$replacement =  GetUserLink($userid);
			break;
		default:    // unknown tag => reconstruct and return original expression
			$replacement = '[' . $tag . ']' . $inner_string . '[/' . $tag .']';
			break;
	}
	return $replacement;
}

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
	if(!isset($_GET['read']) || !is_numeric($_GET['id'])){
?>
<table>
<tr><td><b>Gelesen</b>:&nbsp;</td><td><b>Betreff</b>:&nbsp;&nbsp;&nbsp;</td><td><b>Von:</b></td><td><b>Gespr&auml;chs-<br/>partner</b>:</td><td><b>Gespr&auml;chspartner<br/>gelesen</b>:</td></tr>
<tr><td>&nbsp;</td><td></td><td></td><td></td></tr>
<?php
	$erg = mysql_query("SELECT * FROM `holopost_betreffs` ORDER BY lastedit DESC;");
	if(!$erg) die("Falscher Query: ".$sql_query);
	while($out = mysql_fetch_object($erg)){
		$partnerid = ($out->to_id);
		$selfid = ($out->from_id);
		$read = ($out->read_from);
		$otherread = ($out->read_to);
?>
<tr><td style="text-align:right;"><img src="../../pics/<?=($read)?"HolomailGelesen.jpg":"HolomailNeu.jpg"?>" style="height:20px; text-align:right;" /></td><td><a href="holopost.php?read&id=<?=$out->id?>"><?=$out->betreff?></a></td><td><?=GetUserLink($selfid)?></td><td><?=GetUserLink($partnerid)?></td><td><img src="../../pics/<?=($otherread)?"HolomailGelesen.jpg":"HolomailNeu.jpg"?>" style="height:20px;" /></td></tr>
<?php
	}
	mysql_free_result($erg);
?>
</table>
<?php
	}
	else {
		if(!($out = SqlObjQuery("SELECT * FROM `holopost_betreffs` WHERE id={$_GET['id']};"))){
			echo "Kein Recht auf diese Holonachricht!<br/>";
		}
		else{
			$partnerid = ($out->to_id);
			$selfid = ($out->from_id);
			$read = ($out->read_from);
			$otherread = ($out->read_to);
?>
<table>
	<tr><td valign="top" style='background-color:#999'>Betreff:</td><td valign="top" style='background-color:#999'><?=$out->betreff?></td></tr>
	<tr><td valign="top" style='background-color:#999'>Gespr&auml;chspartner:&nbsp;&nbsp;&nbsp;</td><td valign="top" style='background-color:#999'><?=GetUserLink($partnerid)?></td></tr>
</table>

<?php		
			
			$sql_query = "SELECT * FROM `holopost_nachrichten` WHERE `holopostid`='{$_GET['id']}' ORDER BY `id`";
			$erg = mysql_query($sql_query);
?>
<br/>
<table colspan="1" border="0">
<?php
			$counter = 0;
			while($out = mysql_fetch_object($erg)){
				$counter++;
?>
	<tr><td valign="top" style='background-color:#<?=($counter%2)?'BBB':'999'?>;'><?=GetUserLink($out->userid)?><br/><?=GetUserPlatz($out->userid)?>. Platz<br/><?=GetUserPoints($out->userid)?> Punkte<br/></td><td  valign="top" style='background-color:#<?=($counter%2)?'BBB':'999'?>;'><?=$out->message?></td></tr>
<?php
			}
?>
</table><br/>
<?php
			mysql_free_result($erg);
		}
	}
?>
</center>
		</td>
	</tr>
</table>
		<?php
	}
?>
</body>
</html>