 <?php
  include_once("data/funcs.php");
?>
<?php
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

;entfernen für reset
?> 