<?php
require('common.php');

connect();

// Removes % and escapes the input
$q = $_GET['q'];
$q = str_replace('%', '', $q);
$q = mysql_real_escape_string($q);
$t = $_GET['t'];

if($t != 'tubes' and $t !='boroughs') die('Heh. I see what you did there.');

if (strlen($q) > 0)
{
?>
<ul>
<?php
$max_results = 3;
$result = query("SELECT name FROM $t WHERE name LIKE '$q%' LIMIT $max_results");
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<li ';
  echo 'onclick="choice(\''.$line['name'].'\')">';
  echo $line['name'];
  echo '</li>';
}
if($t=='tubes') 
  echo '<li><a href="stations.php" target="_blank">View all stations</a></li>';
?>
</ul>
<?php
}
?>
