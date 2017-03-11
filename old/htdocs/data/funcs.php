<?php
	{//Initialisierung
	include("data/settings.php");
	$db = mysql_connect($settings['database']["server"], $settings['database']["user"],$settings['database']["passwort"]) or die ("Fehler: 1");
	mysql_select_db($settings['database']["db"]) or die ("Fehler: 2");
	session_start(); 
	include("data/buildings.php");   
	include("data/units.php");
	$worldroot = "http://".$_SERVER['HTTP_HOST']."/".$settings['rootfolder']."";
	foreach($databasesettingssave as $setting){
		$out = SqlObjQuery("SELECT wert FROM gamesettings WHERE einstellung = '{$setting}'");
		if($out){
			if($out->wert == $settings[$setting]){
			}
			else{
				$oldvalue = $out->wert;
				$newvalue = $settings[$setting];
				mysql_query("UPDATE gamesettings SET wert = {$settings[$setting]} WHERE einstellung = '{$setting}'");
				
				switch($setting){
					case 'buildspeed': mysql_query("UPDATE event_build SET end = (((end-UNIX_TIMESTAMP())*{$oldvalue}/{$newvalue})+UNIX_TIMESTAMP())"); break;
					case 'recruitspeed': mysql_query("UPDATE event_recruit SET end_unit = (((end_unit-UNIX_TIMESTAMP())*{$oldvalue}/{$newvalue})+UNIX_TIMESTAMP()),timePerUnit = (timePerUnit*{$oldvalue}/{$newvalue})"); break;
					case 'attackspeed':{
						mysql_query("UPDATE event_troops_attack SET ankunft = (((ankunft-UNIX_TIMESTAMP())*{$oldvalue}/{$newvalue})+UNIX_TIMESTAMP()), laufzeit = (laufzeit*{$oldvalue}/{$newvalue})");
						mysql_query("UPDATE event_troops_back SET ankunft = (((ankunft-UNIX_TIMESTAMP())*{$oldvalue}/{$newvalue})+UNIX_TIMESTAMP())");
						mysql_query("UPDATE event_troops_back_def SET ankunft = (((ankunft-UNIX_TIMESTAMP())*{$oldvalue}/{$newvalue})+UNIX_TIMESTAMP())");
						mysql_query("UPDATE event_troops_defense SET ankunft = (((ankunft-UNIX_TIMESTAMP())*{$oldvalue}/{$newvalue})+UNIX_TIMESTAMP())");
						}break;
				}
			}
		}
		else mysql_query("INSERT INTO gamesettings ( einstellung, wert ) VALUES ( '{$setting}', {$settings[$setting]} )");
	}
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
function GetBBLeisteJAVASCRIPT($formid,$textboxid){
	$bbCodes = array('b' => "Fett",'u' => "Unterstrichen",'i' => "Kursiv",'player' => "Spieler",'allianz' => "Allianz");
	$out = "";
	foreach($bbCodes as $key => $cont){
		$out .= "<img onclick='insert(\\'{$key}\\',\\'{$key}\\',\\'{$formid}\\',\\'{$textboxid}\\');' src='{$key}.png' alt='{$cont}' title='{$cont}' />\n&nbsp;";
	}
	return $out."<br/>";
}
function getUserLink($userid){
	if($username = getUserName($userid)){
		return "<a href=\"player.php?id={$userid}\">{$username}</a>";
	}
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
function getPlanetName($planetid){
	$sql = "SELECT * FROM planets WHERE id = '{$planetid}'";
	if($out = SqlObjQuery($sql)) return $out->planetname;
	return "---";
}
function getUserName($userid){
	$sql = "SELECT username FROM user WHERE id = '{$userid}'";
	$erg = mysql_query($sql);
	if($out = mysql_fetch_object($erg)){
		mysql_free_result($erg);
		return preg_replace("~ ~","&nbsp;",$out->username);
	}
	mysql_free_result($erg);
	return 0;
}
function SendBericht($userid,$betreff,$nachricht){
	mysql_query("INSERT INTO `holoberichte` ( `gettime` , `userid` , `betreff` , `inhalt`, `time` )
VALUES (
'".date('d.m.y \\u\\m H:i:s')."', '{$userid}', '".htmlentities($betreff,ENT_QUOTES)."', '".$nachricht."', '".time()."');");
}
function time_duration($seconds, $use = null, $zeros = false){
    // Define time periods
    $periods = array (
        'Jahre'     	=> 31556926,
        'Monate'    	=> 2629743,
        'Wochen'    	=> 604800,
        'Tage'     	 	=> 86400,
        'h.'  	   		=> 3600,
        'min.'  	 	=> 60,
        'sek.'   		=> 1
        );

    // Break into periods
    $seconds = (float) $seconds;
    $segments = array();
    foreach ($periods as $period => $value) {
        if ($use && strpos($use, $period[0]) === false) {
            continue;
        }
        $count = floor($seconds / $value);
        if ($count == 0 && !$zeros) {
            continue;
        }
        $segments[$period] = $count;
        $seconds = $seconds % $value;
    }

    // Build the string
    $string = array();
    foreach ($segments as $key => $value) {
        $segment = $value . ' ' . $key;
        $string[] = $segment;
    }

    return implode(' ', $string);
}
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
function GetDistance($planet1,$planet2){
	if(is_numeric($planet1) && is_numeric($planet2)){
		$p1 = SqlObjQuery("SELECT xcoords, ycoords FROM `planets` WHERE id = {$planet1}");
		$p2 = SqlObjQuery("SELECT xcoords, ycoords FROM `planets` WHERE id = {$planet2}");
		if($p1 && $p2) return sqrt( (($p1->xcoords - $p2->xcoords) * ($p1->xcoords - $p2->xcoords)) + (($p1->ycoords - $p2->ycoords) * ($p1->ycoords - $p2->ycoords)) );
	}
	return 0;
}
function DrawHeader($titel){
	if($_SESSION['Logon'] == true){
		if(isset($_GET["changeplanet"]) && is_numeric($_GET["changeplanet"])){
			$_SESSION['planetid'] = $_GET["changeplanet"];
			?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Destyny War</title>
  <style type="text/css">
body { background-image: url(pics/normbg.jpg);
  </style>
  <script type="text/javascript">
	window.location.href = "<?=$_SERVER['SCRIPT_NAME']?>";
  </script>
</head>
<body style="background-color: rgb(0, 0, 0); color: rgb(255, 0, 0);" link="#F00" vlink="#F00" alink="#F00" text="#F00"><?php //print_r($_SERVER); ?></body>
</html>
				<?php
				exit();
		}
		mysql_query("UPDATE `user` SET `lastacces` = ".time()." WHERE `user`.`id` = {$_SESSION['userid']}  ;");
		$sql = "SELECT * FROM planets WHERE userid = '{$_SESSION['userid']}' and id='{$_SESSION['planetid']}'";
		$erg = mysql_query($sql);
		if(($out = mysql_fetch_object($erg)) == false){
			mysql_free_result($erg);
			$sql = "SELECT * FROM planets WHERE userid = '{$_SESSION['userid']}' ORDER BY planetname";
			$erg = mysql_query($sql);
			if(($out = mysql_fetch_object($erg)) == false){
				if(!GetUserName($_SESSION['userid'])) $_SESSION['Logon'] = false;
				else{
					for($i = 0; $i < $GLOBALS['settings']['minplanets']; $i++) createPlanet($_SESSION['userid'],GetUserName($_SESSION['userid'])."s Planet");
				}
				mysql_free_result($erg);
				//$sql = "SELECT * FROM planets WHERE userid = '{$_SESSION['userid']}'";
				//mysql_query($sql);
				?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Destyny War</title>
  <style type="text/css">
body { background-image: url(pics/startbg.jpg);
  </style>
  <script type="text/javascript">
	window.location.href = window.location.href;
  </script>
</head>
<body style="background-color: rgb(0, 0, 0); color: rgb(255, 0, 0);" link="#F00" vlink="#F00" alink="#F00" text="#F00"></body>
</html>
				<?php
				exit();
			}
			
						
			$_SESSION['planetid'] = $out->id;
			mysql_free_result($erg);
		}
		$o = SqlObjQuery("SELECT count(*) as cnt FROM planets WHERE userid = '{$_SESSION['userid']}'");
		while($o->cnt < $GLOBALS['settings']['minplanets']){
			createPlanet($_SESSION['userid'],GetUserName($_SESSION['userid'])."s Planet");
			$o = SqlObjQuery("SELECT count(*) as cnt FROM planets WHERE userid = '{$_SESSION['userid']}'");
		}
		$sql = "SELECT * FROM allianz_user WHERE userid = '{$_SESSION['userid']}'";
		$erg = mysql_query($sql);
		if(($out = mysql_fetch_object($erg)) == false) $_SESSION['allianz'] = NULL;
		else{
			mysql_free_result($erg);
			$sql = "SELECT * FROM allianzen WHERE id = '{$out->allianzid}'";
			$erg = mysql_query($sql);
			$out2 = mysql_fetch_object($erg);
			mysql_free_result($erg);
			
			$_SESSION['allianz_name'] = $out2->allianzname;
			$_SESSION['allianz'] = $out->allianzid;
			$keys = array("titel" => $out->titel,"gruender" => $out->gruender,"fuehrung" => $out->fuerhrung,"diplomatie" => $out->diplomatie,"einladen" => $out->einladen,"forum" => $out->forum,"forumtype1" => $out->forumtype1,"forumtype2" => $out->forumtype2, "shoutbox" => $out->shoutbox);
			// foreach($keys as $key => $content){
			// $_SESSION['allianz_data'][$key] = $content;
			$_SESSION['allianz_data'] = array("titel" => $out->titel,"gruender" => $out->gruender,"fuehrung" => $out->fuerhrung,"diplomatie" => $out->diplomatie,"einladen" => $out->einladen,"forum" => $out->forum,"forumtype1" => $out->forumtype1,"forumtype2" => $out->forumtype2, "shoutbox" => $out->shoutbox);
			// }
		}
	}
	else{  ///////////////////////////////////////////////////////////////////////////////kill if not logged in
	  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Destyny War</title>
  <style type="text/css">
body { background-image: url(pics/startbg.jpg);
  </style>
  <script type="text/javascript">
	window.location.href = ".";
  </script>
</head>
<body style="background-color: rgb(0, 0, 0); color: rgb(255, 0, 0);" link="#F00" vlink="#F00" alink="#F00" text="#F00"></body>
</html>
	  <?php
	  exit();
	  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Destyny War - <?=getPlanetName($_SESSION['planetid'])?> - <?=htmlentities($titel,ENT_QUOTES)?></title>
  <link rel="shortcut icon" href="pics/logo.jpg"/>
  <style type="text/css">
body { 
	font-family: Arial;
	background-color: rgb(0, 0, 0);
	color: rgb(255, 255, 255);
	background:#2C2C2C url(pics/starbg.png) repeat;
}

.background_layer { 
	position:absolute; 
	top:0; 
	left:0; 
	z-index:-1; 
	width:100%; 
	height:652px; 
	background:transparent url(pics/bg_image.jpg) no-repeat top center; 
}

.tp {
	opacity: 0.700;
}


* { font-size: 4mm; }
h1 { font-size: 5mm;}
h2 { font-size: 5mm;}
h3 { font-size: 5mm;}
h4 { font-size: 5mm;}
h5 { font-size: 5mm;}
h6 { font-size: 5mm;}

a:link { color:#FFFFEE; }
a:visited { color:#FFFFEE; }
a:active { color:#FFFFEE; }

.kasten { 
	border: 3px inset rgb(0, 0, 0);
}
.brett { 
	border: 3px outset rgb(0, 0, 0);
}



  </style>
  <script type="text/javascript">
	function spoiler(ref) {
		var display_value = ref.parentNode.getElementsByTagName('span')[0].style.display;
		if(display_value == 'none'){
			ref.parentNode.getElementsByTagName('span')[0].style.display = 'block';
		}
		else {
			ref.parentNode.getElementsByTagName('span')[0].style.display = 'none';
		}
		var buttontext = ref.value;
		if(buttontext == 'aufklappen') ref.value ='zuklappen';
		else ref.value = 'aufklappen';
	}
	function insert(aTag, eTag, formname, elementname) { 
		var input = document.forms[formname].elements[elementname];
		input.focus();  /* für Internet Explorer */  
		if(typeof document.selection != 'undefined') {    /* Einfügen des Formatierungscodes */    
			var range = document.selection.createRange();    
			var insText = range.text;    
			range.text = aTag + insText + eTag;    /* Anpassen der Cursorposition */    
			range = document.selection.createRange();    
			if (insText.length == 0) {      
				range.move('character', -eTag.length);    
			} else {      
				range.moveStart('character', aTag.length + insText.length + eTag.length);          
			}    range.select();  
		}  /* für neuere auf Gecko basierende Browser */  
		else {
			if(typeof input.selectionStart != 'undefined')  {    /* Einfügen des Formatierungscodes */    
				var start = input.selectionStart;    
				var end = input.selectionEnd;    
				var insText = input.value.substring(start, end);    
				input.value = input.value.substr(0, start) + aTag + insText + eTag + input.value.substr(end);    /* Anpassen der Cursorposition */    
				var pos;    
				if (insText.length == 0) {      
					pos = start + aTag.length;    
				} else {      
					pos = start + aTag.length + insText.length + eTag.length;    
				}  
				input.selectionStart = pos;  
				input.selectionEnd = pos;
			}  /* für die übrigen Browser */  
			else  {    /* Abfrage der Einfügeposition */    
				var pos;    var re = new RegExp('^[0-9]{0,3}$');    
				while(!re.test(pos)) {      
					pos = prompt("Einfügen an Position (0.." + input.value.length + "):", "0");    
				}    
				if(pos > input.value.length) {     
					pos = input.value.length;    
				}    /* Einfügen des Formatierungscodes */    
				var insText = prompt("Bitte geben Sie den zu formatierenden Text ein:");    
				input.value = input.value.substr(0, pos) + aTag + insText + eTag + input.value.substr(pos); 
			}
		}
	}
	function sleep(prmSec)
	{
	  var eDate = null;
	  var eMsec = 0;
	  var sDate = new Date();
	  var sMsec = sDate.getTime();

	  do {
		  eDate = new Date();
		  eMsec = eDate.getTime();

	  } while ((eMsec-sMsec)<prmSec);
	}

	
	var Zeit = new Date();
	var localet = Zeit.getTime();
	var servert = <?=time()*1000?>;
	var diff = localet - servert;
	function rekursivtimer(){
		var now = new Date();
		var nowserver = new Date(now.getTime()-diff);
		document.getElementById('servertime').innerHTML = "Serverzeit: "+(nowserver.toLocaleString());
		window.setTimeout("rekursivtimer()", 500);
		delete now;
		delete nowserver;
	}
	function time_duration(seconds)
	{
		var outstring = "";
		var mustshow = false; 
		if(Math.floor(seconds/3600) > 0){
			outstring += (Math.floor(seconds/3600)+"&nbsp;Stunden, ");
			mustshow = true;
		}
		seconds %= 3600
		if((Math.floor(seconds/60) > 0) || mustshow){
			outstring += (Math.floor(seconds/60)+"&nbsp;Minuten, ");
			mustshow = true;
		}
		seconds %= 60;
		if((Math.floor(seconds/1) > 0) || mustshow){
			outstring += (Math.floor(seconds/1)+"&nbsp;Sekunden");
			mustshow = true;
		}
		
		return outstring;
	}
	function rekursivrestzeit(elementid,end){
		var now = new Date();
		var nowserver = new Date(now.getTime()-diff);
		document.getElementById(elementid).innerHTML = time_duration(end-nowserver.getTime()/1000);
		if(time_duration(end-nowserver.getTime()/1000) == ""){
			location.reload();
			return;
		}
		window.setTimeout("rekursivrestzeit('"+elementid+"',"+end+")", 500);
		delete now;
		delete nowserver;
	}
	function SetTimeDur(elementid,sec){
		document.getElementById(elementid).innerHTML = time_duration(sec);
	}
  </script>
  
<script src="scripte/prototype.js" type="text/javascript"></script>
<script src="scripte/scriptaculous.js" type="text/javascript"></script>
  
</head>
<body id="body" class="body" name="body" onload="rekursivtimer();">
<center>
<table border="0" cellpadding="1" cellspacing="3" style="width:100%;">
  <tbody>
	<tr>
		<td colspan="3" style="background-color:#111;width:100%;" class="biggersize tp">
			<center>
				<a href="logout.php">Logout</a>&nbsp;
				<a href="karte.php">Karte</a>&nbsp;
				<a href="planet.php?id=<?=$_SESSION['planetid']?>">Planet</a>&nbsp;
				<a href="planetsoverview.php">eigene&nbsp;Planeten</a>&nbsp;
				<a href="rang.php">Rangliste(<?=getUserPlatz($_SESSION['userid'])?>.|<?=getUserPoints($_SESSION['userid'])?>&nbsp;Punkte)</a>&nbsp;
	  <?php
		if($_SESSION['allianz']){
			$o1 = SqlObjQuery("SELECT count(`id`) as cnt FROM `allianzen_themen_gelesen` WHERE `userid` = {$_SESSION['userid']} and `themaid` IN (SELECT `id` FROM `allianzen_themen` WHERE `forumid` IN (SELECT `id` FROM `allianzen_foren` WHERE `allianzid` = '{$_SESSION['allianz']}' and (`forumtype1`= false or `forumtype1`= ".(($_SESSION['allianz_data']['forumtype1'])?"true":"false").") and (`forumtype2`= false or `forumtype2`= ".(($_SESSION['allianz_data']['forumtype2'])?"true":"false").")))");
			$o2 = SqlObjQuery("SELECT count(`id`) as cnt FROM `allianzen_themen` WHERE `forumid` IN (SELECT `id` FROM `allianzen_foren` WHERE `allianzid` = '{$_SESSION['allianz']}' and (`forumtype1`= false or `forumtype1`= ".(($_SESSION['allianz_data']['forumtype1'])?"true":"false").") and (`forumtype2`= false or `forumtype2`= ".(($_SESSION['allianz_data']['forumtype2'])?"true":"false")."))");
			if($o1->cnt != $o2->cnt){
			//echo $o1->cnt,"..",$o2->cnt;
		?>(<a href="allianz_forum.php">neu</a>)<?php }} ?><a href="allianz.php">Allianz</a>&nbsp;
<?php
	$out = SqlObjQuery("SELECT count(*) as COUNTER FROM `holoberichte` WHERE `userid` = {$_SESSION['userid']} AND `read` = 0;");
	//echo $out->COUNTER;
	if($out->COUNTER == 0){
?>
				<a href="holoberichte.php">Holoberichte</a>&nbsp;
<?php
	}
	else {
?>
				<a href="holoberichte.php"><img src="pics/HoloberichtNeu.png" style="height: 20px;" title="<?=$out->COUNTER?> neue Holoberichte"></a>&nbsp;
<?php
	}
?>

				<a href="holopost.php"><?php if(SqlObjQuery("SELECT * FROM `holopost_betreffs` WHERE (del_from = 0 and from_id = {$_SESSION['userid']} and read_from = 0) or (del_to = 0 and to_id = {$_SESSION['userid']} and read_to = 0);")){
?><img src="pics/HolomailNeu.png" title="Neue Holomails" style="height: 20px;"><?php
}else{
?>Holopost<?php
}?></a>&nbsp;
				<a href="settings.php">Einstellungen</a>
                <br/>
                <?php
$incs = SqlObjQuery("SELECT count(*) as cn FROM `event_troops_attack` WHERE `to_planet` IN (SELECT `id` FROM `planets` WHERE `userid` = {$_SESSION['userid']}) ORDER BY `ankunft`");
if($incs && $incs->cn){
	if($incs->cn != 1) 	echo "<b style='background-color:#009;'>Es laufen ".$incs->cn." <a href='show_attacks.php'>Angriffe</a> auf dich.</b>";
	else 				echo "<b style='background-color:#009;'>Es läuft ".$incs->cn." <a href='show_attacks.php'>Angriff</a> auf dich.</b>";
}
	  ?>
			</center>
        </td>
    </tr>
    <tr>
      <tr>
		<td valign="top" style="background-color:#111;width:25%;" class="tp">
			<div>
				<h6>Rohstoffe:<h6>
				<?php
				$out = SqlObjQuery("SELECT * FROM `planets` WHERE `id` = {$_SESSION['planetid']};");
				?>
				<table>
					<tr><td>Platin:</td><td><?=floor($GLOBALS['buildings']["produktion"][$GLOBALS['buildings']["miene"]["Stufe"]]*$GLOBALS['settings']['speed']*$out->moral)."&nbsp;-&nbsp;".$GLOBALS['ress']["Platin"]?></td></tr>
					<tr><td>Stahl:</td><td><?=floor($GLOBALS['buildings']["produktion"][$GLOBALS['buildings']["schmelze"]["Stufe"]]*$GLOBALS['settings']['speed']*$out->moral)."&nbsp;-&nbsp;".$GLOBALS['ress']["Stahl"]?></td></tr>
					<tr><td>Energie:</td><td><?=floor(floor($GLOBALS['buildings']["produktion"][$GLOBALS['buildings']["reaktor"]["Stufe"]]*(100-$GLOBALS['buildings']["reaktor"]["verhaeltnis"])/50)*$GLOBALS['settings']['speed']*$out->moral)."&nbsp;-&nbsp;".$GLOBALS['ress']["Energie"]?></td></tr>
					<tr><td>Plasma:</td><td><?=floor(floor($GLOBALS['buildings']["produktion"][$GLOBALS['buildings']["reaktor"]["Stufe"]]*($GLOBALS['buildings']["reaktor"]["verhaeltnis"])/50)*$GLOBALS['settings']['speed']*$out->moral)."&nbsp;-&nbsp;".$GLOBALS['ress']["Plasma"]?></td></tr>
					<tr><td>Plasmid:</td><td><?=floor($GLOBALS['buildings']["produktion"][$GLOBALS['buildings']["biolabor"]["Stufe"]]*$GLOBALS['settings']['speed']*$out->moral)."&nbsp;-&nbsp;".$GLOBALS['ress']["Plasmid"]?></td></tr>
					<tr><td>Nahrung:</td><td><?=floor($GLOBALS['buildings']["produktion"][$GLOBALS['buildings']["bauer"]["Stufe"]]*$GLOBALS['settings']['speed']*$out->moral)."&nbsp;-&nbsp;".$GLOBALS['ress']["Nahrung"]?></td></tr>
					<tr><td>Freie Bev&ouml;lkerung:</td><td><?=($GLOBALS['buildings']["versorgtePlaetze"][$out->bauer]-$out->troopsGes)?></td></tr>
					<tr><td>Moral:</td><td><?=round($out->moral*100)?>%</td></tr>
				</table>
				<br/>
				<h6>Geb&auml;ude:<h6>
				<table>
					<?php
						foreach($GLOBALS['builds'] as $build => $cont){ if($cont["Stufe"] != 0){?>
					<tr><td><a href="<?=$build?>.php"><?=$cont["Name"]?></a></td><td>(Stufe:&nbsp;<?=$cont["Stufe"]?>)</td></tr>
					<?php }}
					
					?>
				</table>
			</div>
		</td>
      </td>
      <td valign="top" style="background-color:#111;width:50%;" class="tp">
			<div>
				<center>
				<br>
	
<?php
}


function DrawFooter(){
?>
				</center>
			</div>
        </td>
		<td valign="top" style="background-color:#111;width:25%;" class="tp">
			<div>
			<h6>Einheiten:<h6>
			<table>
				<?php
					$unums = SqlArrQuery("SELECT * FROM `planets` WHERE id = {$_SESSION['planetid']}");
					$tmp = 0;
					foreach($GLOBALS['units'] as $unit => $unitdata)
					{
						$tmp += $unums[$unit];
						if(!$unums[$unit]) continue;
				?>
				<tr>
					<td>
						<?=$unitdata['Name']?>
					</td>
					<td>
						:
					</td>
					<td>
						<?=$unums[$unit]?>
					</td>
				</tr>
				<?php
					}
					
				?>
			</table><br/><br/>
			<?php
				if(!$tmp) echo 'keine<br/><br/><br/>';
				?>
				<?php
$out = SqlObjQuery("SELECT count(id) as cnt FROM `event_troops_attack` WHERE `from_planet` = {$_SESSION['planetid']} ORDER BY `ankunft`");
if($out->cnt){
?>
Laufende Angriffe:<br/><br/>
<table border="1">
<tr><th>Herkunft</th><th>Ziel</th><th>Ankunft</th><th>Ankunft in</th>
</tr>
<?php
	$erg = mysql_query("SELECT * FROM `event_troops_attack` WHERE `from_planet` = {$_SESSION['planetid']} ORDER BY `ankunft`");
	$count = 0;
	while($out = mysql_fetch_array($erg)){
		$count++;
?>
<tr><td><?=GetPlanetLink($out['from_planet'])?></td><td><?=GetPlanetLink($out['to_planet'])?></td><td><?=date('d.m.y\\<\\b\\r\\/\\>H:i:s',$out['ankunft'])?></td><td><b id='restzeitA<?=$count?>' > Berechne...</b><script type="text/javascript">window.setTimeout("rekursivrestzeit('restzeitA<?=$count?>',<?=$out['ankunft']?>)", 500);</script></td>
</tr>
<?php
	};
	mysql_free_result($erg);
?>
</table>
<hr/>
<?php
}

$out = SqlObjQuery("SELECT count(id) as cnt FROM `event_troops_attack` WHERE `to_planet` = {$_SESSION['planetid']} ORDER BY `ankunft`");
if($out->cnt){
?>
Eintreffende Angriffe:<br/><br/>
<table border="1">
<tr><th>Herkunft</th><th>Ziel</th><th>Ankunft</th><th>Ankunft in</th></tr>
<?php
	$erg = mysql_query("SELECT * FROM `event_troops_attack` WHERE `to_planet` = {$_SESSION['planetid']}  ORDER BY `ankunft`");
	while($out = mysql_fetch_array($erg)){
		$count++;
?>
<tr><td><?=GetPlanetLink($out['from_planet'])?></td><td><?=GetPlanetLink($out['to_planet'])?></td><td><?=date('d.m.y\\<\\b\\r\\/\\>H:i:s',$out['ankunft'])?></td><td><b id='restzeitA<?=$count?>' > Berechne...</b><script type="text/javascript">window.setTimeout("rekursivrestzeit('restzeitA<?=$count?>',<?=$out['ankunft']?>)", 500);</script></td></tr>
<?php
	}
?>
</table>
<hr/>
<?php
}
?>
			</div>
		</td>
    </tr>
	<tr>
		<td>
		</td>
		<td colspan="2" align="right" style="font-size:smaller">
			<div  id="servertime" name="servertime" style="font-size:90%">Serverzeit:</div>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<center>
				<?php
				$out = SqlObjQuery("SELECT shoutbox FROM user WHERE id = {$_SESSION['userid']}");
                mysql_query("DELETE FROM `shoutboxen` WHERE `allianz` NOT IN (SELECT `id` FROM `allianzen`) and `allianz` <> -1;");
                mysql_query("DELETE FROM `shoutbox_user` WHERE `user` NOT IN (SELECT `id` FROM `user`) or `shoutbox` NOT IN (SELECT `id` FROM `shoutboxen`);");
                mysql_query("DELETE FROM `shoutbox_user` WHERE shoutbox = 1;");
                mysql_query("INSERT INTO `shoutbox_user` (shoutbox ,user) SELECT 1,id FROM `user` WHERE userpasswd<>'';");
                mysql_query("REPLACE INTO `shoutboxen` ( `id` , `allianz` , `name` ) VALUES ( 1, -1, 'Alle User' );");
                $sid = $out->shoutbox;
                if(is_numeric($sid)){
                    if(SqlObjQuery("SELECT * FROM shoutbox_user WHERE user={$_SESSION['userid']} and shoutbox={$sid}")) {
                        $sid = $sid;
                    }
                    else $sid = 1;
                }
                else $sid = 1;
                $erg = mysql_query("SELECT * FROM shoutbox_user WHERE user={$_SESSION['userid']}");
                ?>                
                <form method="POST" onsubmit="return sendMessage(this.sender);">
                    <select name="shout" size="1" onchange="selectshout(this.form.shout.options[this.form.shout.selectedIndex].value)">
                <?php
                while($out = mysql_fetch_object($erg)){
                    $out2 = SqlObjQuery("SELECT * FROM shoutboxen WHERE id={$out->shoutbox}");
                ?>
                        <option label="<?=$out2->name?>" value="<?=$out2->id?>" <?=($sid == $out2->id)?'selected="selected"':''?> ><?=$out2->name?></option>
                <?php
                }
                ?>
                    </select>
                    <input id="sender" name="sender" type="text"/>
                    <input type="submit" value="Nachricht senden"/>
                </form></div>
                <div name="output"><table border="0" cellspacing="3">
                <?php
                $erg = mysql_query("SELECT * FROM `shoutbox_nachrichten` WHERE `shoutbox` ={$sid} ORDER BY `id` DESC LIMIT 0, 10");
                while($out = mysql_fetch_object($erg)){
                ?>
                    <tr><td align="top" style="background-color:#444;"><?=GetUserLink($out->user)?></td><td align="top" style="background-color:#333;"><?=$out->message?></td></tr> 
                <?php
                }
                ?>
                </table>
                <script type="text/javascript">
					function urlEncode(str){
						str=escape(str);
						str=str.replace(new RegExp('\\+','g'),'%2B');
						return str.replace(new RegExp('%20','g'),'+');
					}

                    var shoutboxid = <?=$sid?>;
                    
                    
                    function selectshout(id) {
                        shoutboxid = id;
                        send();
                    }
                    
                    function sendMessage(thisobj){
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
                        var message = thisobj.value;
                        thisobj.value = '';
                        //alert(message);
                        if(req){
                            var params = "sender="+urlEncode(message);
                            req.open("POST", '<?=$worldroot?>shoutbox.php?id='+shoutboxid+'&send', true);
                            //alert('<?=$worldroot?>shoutbox.php?id='+shoutboxid+'&getdata');
                            req.onreadystatechange = function(){ 
                                switch(req.readyState) {
                                case 4:
                                    if(req.status!=200) {
                                        alert("Fehler:"+req.status); 
                                    }else{
                                        var html = req.responseText;
                                        document.getElementById('shoutboxsaver').innerHTML = html;
                                        document.getElementsByName('output')[0].innerHTML = document.getElementsByName('output')[1].innerHTML;
                                        //alert("funzet");
                                    }
                                    break;
                                default:
                                    return false;
                                    alert("Kann nicht senden!");
                                break;     
                                }
                            }
                            req.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                            req.send(params);
                        }else alert("Kann nicht senden!");
                        return false;
                    }
                    
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
                            req.open("GET", '<?=$worldroot?>shoutbox.php?id='+shoutboxid+'&getdata', true);
                            //alert('<?=$worldroot?>shoutbox.php?id='+shoutboxid+'&getdata');
                            req.onreadystatechange = function(){ 
                                switch(req.readyState) {
                                case 4:
                                    if(req.status!=200) {
                                        //alert("Fehler:"+req.status); 
                                    }else{
                                        var html = req.responseText;
                                        document.getElementById('shoutboxsaver').innerHTML = html;
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
			</center>
		</td>
	</tr>
  </tbody>
</table>
</center>
<div class="background_layer">&nbsp;</div>
<div id="temp" name="temp" style="display:none;">
    <div id="shoutboxsaver"></div>
    <div id="kartesaver"></div>
</div>
</body>
</html>
<?php
}

function mainpageheader(){
?>
<tr class="tp">
	<td>
		<a href="impessum.php">Impressum</a>&nbsp;&nbsp;<a href="impessum.php">Forum</a>&nbsp;&nbsp;<a href="help2.php">Hilfe</a>
	</td>
</tr>
<?php
}

function DrawAllianzTable(){
?>
		|<a href="allianz_overview.php">&Uuml;bersicht</a>|
		<a href="allianz_user.php">Mitglieder</a>|
		<a href="allianz_einladen.php">Einladungen</a>|
		<a href="allianz_forum.php">Forum</a>|
		<?php
		if($_SESSION['allianz_data']['forum']){
		?>
		<a href="allianzen_forum_admin.php">Forum&nbsp;Administrieren</a>|
		<?php
		}
		?>
		<?php
		if($_SESSION['allianz_data']['gruender']){
		?>
		<a href="allianz_admin.php">Allianz&nbsp;Administrieren</a>|
		<?php
		}
		?>
		<?php
		if($_SESSION['allianz_data']['shoutbox']){
		?>
		<a href="shoutbox_admin.php">Shoutboxen&nbsp;Administrieren</a>|
		<?php
		}
		?>
<?php
}


  
function createPlanet($user = -1,$name = "Alienplanet"){ //user == -1 = Ohne Besitzer
	if($user == -1) $name = "Alienplanet";
	$sql = "SELECT * FROM variablen where variable = 'grad'";
	$erg = mysql_query($sql);
	$out = mysql_fetch_object($erg);
	$grad = $out->inhalt;
	mysql_free_result($erg); 
	$sql = "SELECT * FROM variablen where variable = 'entfernung'";
	$erg = mysql_query($sql);
	$out = mysql_fetch_object($erg);
	$entfernung = $out->inhalt;
	mysql_free_result($erg);
	
	if($grad >= 360){
		$grad -= 360;
		$entfernung++;
	}
	$x = (round(cos($grad)*$entfernung));
	$y = (round(sin($grad)*$entfernung));
	$grad += 360/(($entfernung+1)*pi())*4;
	
	$sql = "UPDATE variablen SET inhalt = '{$grad}' WHERE variable='grad'";
	mysql_query($sql);
	$sql = "UPDATE variablen SET inhalt = '{$entfernung}' WHERE variable='entfernung'";
	mysql_query($sql);
	if(SqlObjQuery("SELECT * FROM `planets` WHERE `xcoords` = {$x} AND `ycoords` = {$y}")){
		createPlanet($user,$name);
	}
	else{
		$sql = "INSERT INTO `planets` ( `userid` , `planetname` , `xcoords` , `ycoords` , `platin` , `energie` , `stahl` , `plasma` , `plasmid` , `nahrung` , `lagerhalle` , `biolabor` , `senat` , `miene` , `reaktor` , `reaktorplasmaverhaeltnis` , `schmelze` , `markt` , `bauer` , `schildgenerator` , `raumhafen` , `lastplus`)
			VALUES (
			'{$user}', '{$name}', '{$x}', '{$y}', '1000000', '1000000', '1000000', '1000000', '1000000', '1000000', '0', '0', '1', '0', '0', '50', '0', '0', '0', '0', '0',".time()."
			);";
		mysql_query($sql);
		$out = SqlObjQuery("SELECT count(id) as cnt FROM planets");
		if(!($out->cnt % 3)) createPlanet();
		mysql_query("INSERT INTO `variablen` ( `variable` , `inhalt` ) VALUES ( 'punkte_akt', '');");
	}
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
function BBCodesLeiste($form,$feld){
?>
<table>
	<tr>
		<td><a href="javascript:insert('[b]','[/b]'			,'<?=$form?>','<?=$feld?>');"><img src="pics/BBCodes/Fett.jpg" height="16" width="16" alt="[b]" title="Fett"/></a></td>
		<td><a href="javascript:insert('[i]','[/i]'			,'<?=$form?>','<?=$feld?>');"><img src="pics/BBCodes/Kusiv.jpg" height="16" width="16" alt="[i]" title="Kusiv"/></a></td>
		<td><a href="javascript:insert('[u]','[/u]'			,'<?=$form?>','<?=$feld?>');"><img src="pics/BBCodes/Unterstrichen.jpg" height="16" width="16" alt="[u]" title="Unterstrichen"/></a></td>
		<td><a href="javascript:insert('[url]','[/url]'		,'<?=$form?>','<?=$feld?>');"><img src="pics/BBCodes/Url.jpg" alt="[url]" height="16" width="16" title="Link einf&uuml;gen"/></a></td>
		<td><a href="javascript:insert('[color]','[/color]'	,'<?=$form?>','<?=$feld?>');"><img src="pics/BBCodes/Farbe.jpg" alt="[color]" height="16" width="16" title="Schriftfarbe &auml;dern"/></a></td>
		<td><a href="javascript:insert('[size]','[/size]'		,'<?=$form?>','<?=$feld?>');"><img src="pics/BBCodes/Groesse.jpg" alt="[size]" height="16" width="16" title="Schriftgr&ouml;&szlig;e &auml;dern"/></a></td>
		<td><a href="javascript:insert('[quote]','[/quote]'	,'<?=$form?>','<?=$feld?>');"><img src="pics/BBCodes/Zitat.jpg" height="16" width="16" alt="[quote]" title="Zitat"/></a></td>
		<td><a href="javascript:insert('[spoiler]','[/spoiler]','<?=$form?>','<?=$feld?>');"><img src="pics/BBCodes/Spoiler.jpg" height="16" width="16" alt="[spoiler]" title="Spoiler"/></a></td>
		<td><a href="javascript:insert('[img]','[/img]'		,'<?=$form?>','<?=$feld?>');"><img src="pics/BBCodes/Bild.jpg" height="16" width="16" alt="[img]" title="Bild"/></a></td>
		<td><a href="javascript:insert('[player]','[/player]'	,'<?=$form?>','<?=$feld?>');"><img src="pics/BBCodes/Player.jpg" height="16" width="16" alt="[player]" title="Spieler einf&uuml;gen"/></a></td>
		<td><a href="javascript:insert('[planet]','[/planet]'	,'<?=$form?>','<?=$feld?>');"><img src="pics/BBCodes/Planet.jpg" height="16" width="16" alt="[planet]" title="Planet - Verwendung: [planet](x|y)[/planet]"/></a></td>
	</tr>
</table>

<?php
}
?>