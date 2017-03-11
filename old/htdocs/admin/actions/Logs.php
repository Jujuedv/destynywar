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
function junserialize($in){
	while(1){
		break;
	}
}
$gid = $_GET['id'];
$erg = mysql_query("SELECT * FROM `logs` WHERE `id` = {$gid}");
$out = mysql_fetch_object($erg);
$out = html_entity_decode($out->data, ENT_QUOTES);
//echo htmlentities(serialize(array('session' => $_SESSION,'request' => $_REQUEST, 'server' => $_SERVER, 'cookie' => $_COOKIE)), ENT_QUOTES);
;
$out2 = junserialize(//html_entity_decode(//htmlentities(serialize(array('session' => $_SESSION,'request' => $_REQUEST, 'server' => $_SERVER, 'cookie' => $_COOKIE)), ENT_QUOTES), ENT_QUOTES));
'
a:4:{s:7:"session";a:6:{s:11:"admin_login";b:1;s:5:"Logon";b:1;s:6:"userid";s:1:"1";s:8:"username";s:6:"qwasyx";s:8:"planetid";s:1:"1";s:7:"allianz";N;}s:7:"request";a:3:{s:2:"id";s:1:"1";s:14:"ajax_chat_lang";s:2:"de";s:9:"PHPSESSID";s:32:"19ce26b4db6956e772c914c290863323";}s:6:"server";a:35:{s:9:"HTTP_HOST";s:9:"localhost";s:15:"HTTP_USER_AGENT";s:74:"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:2.0b7) Gecko/20100101 Firefox/4.0b7";s:11:"HTTP_ACCEPT";s:63:"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";s:20:"HTTP_ACCEPT_LANGUAGE";s:35:"de-de,de;q=0.8,en-us;q=0.5,en;q=0.3";s:20:"HTTP_ACCEPT_ENCODING";s:13:"gzip, deflate";s:19:"HTTP_ACCEPT_CHARSET";s:30:"ISO-8859-1,utf-8;q=0.7,*;q=0.7";s:15:"HTTP_KEEP_ALIVE";s:3:"115";s:15:"HTTP_CONNECTION";s:10:"keep-alive";s:11:"HTTP_COOKIE";s:61:"ajax_chat_lang=de; PHPSESSID=19ce26b4db6956e772c914c290863323";s:18:"HTTP_CACHE_CONTROL";s:9:"max-age=0";s:4:"PATH";s:299:"c:\\Program Files (x86)\\NVIDIA Corporation\\PhysX\\Common;C:\\Windows\\system32;C:\\Windows;C:\\Windows\\System32\\Wbem;C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\;C:\\Program Files (x86)\\TortoiseSVN\\bin;C:\\Program Files (x86)\\QuickTime\\QTSystem\\;C:\\Program Files (x86)\\Java\\jre6\\bin";s:10:"SystemRoot";s:11:"C:\\Windows";s:7:"COMSPEC";s:30:"C:\\Windows\\system32\\cmd.exe";s:7:"PATHEXT";s:53:".COM;.EXE;.BAT;.CMD;.VBS;.VBE;.JS;.JSE;.WSF;.WSH;.MSC";s:6:"WINDIR";s:11:"C:\\Windows";s:16:"SERVER_SIGNATURE";s:133:"<address>Apache/2.2.3 (Win32) DAV/2 mod_ssl/2.2.3 OpenSSL/0.9.8d mod_autoindex_color PHP/4.4.2 Server at localhost Port 80</address> ";s:15:"SERVER_SOFTWARE";s:85:"Apache/2.2.3 (Win32) DAV/2 mod_ssl/2.2.3 OpenSSL/0.9.8d mod_autoindex_color PHP/4.4.2";s:11:"SERVER_NAME";s:9:"localhost";s:11:"SERVER_ADDR";s:9:"127.0.0.1";s:11:"SERVER_PORT";s:2:"80";s:11:"REMOTE_ADDR";s:9:"127.0.0.1";s:13:"DOCUMENT_ROOT";s:42:"C:/Users/julian/Desktop/destyny-war/htdocs";s:12:"SERVER_ADMIN";s:15:"admin@localhost";s:15:"SCRIPT_FILENAME";s:65:"C:/Users/julian/Desktop/destyny-war/htdocs/admin/actions/Logs.php";s:11:"REMOTE_PORT";s:5:"65045";s:17:"GATEWAY_INTERFACE";s:7:"CGI/1.1";s:15:"SERVER_PROTOCOL";s:8:"HTTP/1.1";s:14:"REQUEST_METHOD";s:3:"GET";s:12:"QUERY_STRING";s:4:"id=1";s:11:"REQUEST_URI";s:28:"/admin/actions/Logs.php?id=1";s:11:"SCRIPT_NAME";s:23:"/admin/actions/Logs.php";s:8:"PHP_SELF";s:23:"/admin/actions/Logs.php";s:15:"PATH_TRANSLATED";s:65:"C:/Users/julian/Desktop/destyny-war/htdocs/admin/actions/Logs.php";s:4:"argv";a:1:{i:0;s:4:"id=1";}s:4:"argc";i:1;}s:6:"cookie";a:2:{s:14:"ajax_chat_lang";s:2:"de";s:9:"PHPSESSID";s:32:"19ce26b4db6956e772c914c290863323";}}
'
//, ENT_QUOTES)
);
print_r($out2);
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