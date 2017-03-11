<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html template="true">
<head>
  <title>Destyny War - Admin</title>
  <style type="text/css">
body { 
	background-image: url(../pics/normbg.jpg);
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
	window.location.href = ".";
</script>
	<?php
	}
	else{
		?>
		<ul>
		<?php
		foreach (glob("actions/*.php") as $filename) {
			$tmp = explode( "actions/" , $filename , 2 );
			$ohnePHP = explode( "." , $tmp[1] , -1 );
			echo "<li><a href='$filename'>".$ohnePHP[0]."</a></li>\n";
		}
		?>
		</ul>
		
		<?php
	}
?>
</body>
</html>