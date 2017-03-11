<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html template="true">
<head>
  <title>Destyny War</title>
  <style type="text/css">
body { background-image: url(../pics/startbg.jpg);
  </style>
  <script type="text/javascript">
	window.location.href = "<?php
	session_start();
	if(md5($_POST['pw']) == "2a85d07d0cfa98ce70e1af102221258b"){
		echo "listing.php";
		$_SESSION['admin_login'] = true;
	}
	else echo ".";
?>";
  </script>
</head>
<body style="background-color: rgb(0, 0, 0); color: rgb(255, 0, 0);"></body>
</html>