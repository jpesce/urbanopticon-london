<?php
require_once('common.php');
connect();
    
$name = mysql_real_escape_string(urldecode($_GET['q']));
$t = $_GET['t'];

if($t != 'tubes' and $t !='boroughs') die('0');

if($name=='dunno') echo '1';


$q = "SELECT * FROM $t WHERE name='$name'";
$result = query($q);
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
  $answer = 'ok';
}

if($answer == '') {
  echo '0';
}
else {
  echo '1';
}

?>
