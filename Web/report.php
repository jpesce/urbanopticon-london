<?php
require_once('common.php');
connect();

$id = mysql_real_escape_string($_POST['point_id']);

# Insert answer into DB
$q  = "UPDATE points SET reported=reported+1 WHERE id=$id";
mysql_query($q);
?>
