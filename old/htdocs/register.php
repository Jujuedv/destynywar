<?php
  include_once("data/funcs.php");
  $Fehler = true;
  if(isset($_POST['Send'])){ //Wenn das Formular abgesendet wurde
	$Fehler = false;
	if(empty($_POST['username'])) { $usererr = "Kein Username angegeben"; $Fehler = true; }
	if(empty($_POST['passwort'])) { $pwerr = "Kein Passwort angegeben"; $Fehler = true; } 
	elseif($_POST['passwort'] != $_POST['passwortw']) { $pwwerr = "Passwortwiederholung falsch"; $Fehler = true; }
	
	if(!$Fehler){
		$erg = mysql_query("SELECT id FROM user WHERE username='".htmlentities($_POST['username'],ENT_QUOTES)."'");
		if(!mysql_fetch_row($erg)){
			mysql_query("INSERT INTO user (username, userpasswd) VALUES ('".htmlentities($_POST['username'],ENT_QUOTES)."','".md5(htmlentities($_POST['passwort'],ENT_QUOTES))."')");
		} else { $usererr = "Username schon vergeben"; $Fehler = true; }
		mysql_free_result($erg);

	}
  }
  if($Fehler){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Destyny War</title>
  <link rel="shortcut icon" href="pics/logo.jpg"/>
  <style type="text/css">
body { 
	font-family: Arial;
	background-color: rgb(0, 0, 0);
	color: rgb(255, 255, 255);
	background:#2C2C2C url(pics/star_start.png) repeat;
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
    border-style:solid;
	border-width:1px;
	border-color:#00FF00;
	background-color:#111;
}
.col > td {
}

* { font-size: 5mm; }
h1 { font-size: 20mm;}
h2 { font-size: 9mm;}
h3 { font-size: 8mm;}
h4 { font-size: 7mm;}
h5 { font-size: 6mm;}
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
</head>
<body>
<center>
<table border="0" cellpadding="5" cellspacing="10" class="main">
<?php mainpageheader();?>
    <tr>
      <td class="tp" style="text-align:center;"><h1>Registrierung</h1></td>
    </tr>
		<td class="tp">
		  <form target="_top" method="post" action="register.php" name="login"><br>
			<table border="0" cellpadding="0" cellspacing="0">
			  <tbody>
				<tr>
				  <td>&nbsp;&nbsp;&nbsp;Benutzername: </td>
				  <td><input maxlength="40" size="20" name="username" value="<?php echo $_POST['username']; ?>">&nbsp;&nbsp;</td><td><b style="color:#FF0000;"><?=$usererr?></b></td>
				</tr>
				<tr>
				  <td>&nbsp;&nbsp;&nbsp;Passwort: </td>
				  <td><input maxlength="30" size="20" name="passwort" type="password">&nbsp;&nbsp;</td><td><b style="color:#FF0000;"><?=$pwerr?></b></td>
				</tr>
				<tr>
				  <td>&nbsp;&nbsp;&nbsp;Passwortwiederholung:&nbsp;&nbsp;</td>
				  <td><input maxlength="20" name="passwortw" type="password">&nbsp;&nbsp;</td><td><b style="color:#FF0000;"><?=$pwwerr?></b></td>
				</tr>
				<tr>
				  <td></td>
				  <td><input name="Send" value="Registrieren" type="submit"></td>
				</tr>
			  </tbody>
			</table>
		  </form>
		</td> 
	</tr>
    <tr>
      <td class="tp">
		Du hast schon einen Benutzernamen? Melde dich einfach auf der <a href=".">Startseite</a> an.
	  </td>
    </tr>
  </tbody>
</table>
</center>
<br>
</body>
</html>
<?php } else { //erfolgreiche Registrierung ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Destyny War</title>
  <link rel="shortcut icon" href="pics/logo.jpg"/>
  <style type="text/css">
body { 
	font-family: Arial;
	background-color: rgb(0, 0, 0);
	color: rgb(255, 255, 255);
	background:#2C2C2C url(pics/star_start.png) repeat;
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
    border-style:solid;
	border-width:1px;
	border-color:#00FF00;
	background-color:#111;
}
.col > td {
}

* { font-size: 5mm; }
h1 { font-size: 20mm;}
h2 { font-size: 9mm;}
h3 { font-size: 8mm;}
h4 { font-size: 7mm;}
h5 { font-size: 6mm;}
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
</head>
<body>
<center>
<table border="0" cellpadding="0" cellspacing="10" class="main">
    <tr>
      <td class="tp" style="text-align:center;"><h1>Registrierung erfolgreich!</h1></td>
    </tr>
    <tr>
	  <td class="tp">
		Melde dich nun auf der <a href=".">Startseite</a> mit deinem Beutzernamen und Passwort an.
      </td>
    </tr>
  </tbody>
</table>
</center>
<br>
</body>
</html>

<?php } ?>

