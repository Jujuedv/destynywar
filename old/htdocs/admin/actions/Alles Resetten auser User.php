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
if($_GET['pw'] != "deltafacht") die("falsches Passwort!!");
function getQueriesFromFile($file)
{
    // import file line by line
    // and filter (remove) those lines, beginning with an sql comment token
    $file = array_filter(file($file),
                         create_function('$line',
                                         'return strpos(ltrim($line), "--") !== 0;'));
    // this is a list of SQL commands, which are allowed to follow a semicolon
    $keywords = array('ALTER', 'CREATE', 'DELETE', 'DROP', 'INSERT', 'REPLACE', 'SELECT', 'SET',
                      'TRUNCATE', 'UPDATE', 'USE');
    // create the regular expression
    $regexp = sprintf('/\s*;\s*(?=(%s)\b)/s', implode('|', $keywords));
    // split there
    $splitter = preg_split($regexp, implode("\r\n", $file));
    // remove trailing semicolon or whitespaces
    $splitter = array_map(create_function('$line',
                                          'return preg_replace("/[\s;]*$/", "", $line);'),
                          $splitter);
    // remove empty lines
    return array_filter($splitter, create_function('$line', 'return !empty($line);'));
}

$csvfile = '../../../reset_ohne_user.sql';
if (!is_readable($csvfile)) {
  die("$csvfile does not exist or is not readable");
}
$queries = getQueriesFromFile($csvfile);
foreach($queries as $sql) {
  if (!mysql_query($sql)) {
    die(sprintf("error while executing mysql query #%u: %s\nerror: %s", $i + 1, $sql, mysql_error()));
  }
}
echo count($queries)." queries imported";

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