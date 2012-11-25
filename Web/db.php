<?php
function connect() {
  $link = mysql_connect('localhost', 'user', 'password')
          or die('Could not connect: ' . mysql_error());
  mysql_select_db('urban_psymap') or die('Could not select database');
  return $link;
}

function query($q) {
  $result = mysql_query($q) or die('Query failed: ' . mysql_error());
  return $result;
}
?>
