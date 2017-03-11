 <?php
  include_once("data/funcs.php");
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
      <td class="tp" style="text-align:center;"><h1>Impressum</h1></td>
    </tr>
    <tr>
      <td class="tp">
		  
      </td>
    </tr>
<?php
	$erg = mysql_query("SELECT * FROM `messages` WHERE `del`=0");
	while($out = mysql_fetch_object($erg)) {
		$message = $out->message;
?>
	<tr>
		<td class="tp" style="text-align:center;">
			<?=$message?>
		</td>
	</tr>
<?php
	}
?>
</table>
</center>
<br>
</body>
</html>
