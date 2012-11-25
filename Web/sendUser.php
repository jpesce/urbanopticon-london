<?php
require_once('common.php');
connect();

$data = base64_encode(serialize($_POST));
setcookie('userinfo', $data, time()+3600*24*30*12); //set cookie for 20 years

$username = mysql_real_escape_string($_POST['username']);
$twitter = mysql_real_escape_string($_POST['twitter']);
$email = mysql_real_escape_string($_POST['email']);
$gender = mysql_real_escape_string($_POST['gender']);
$age= mysql_real_escape_string($_POST['age']);
$postcode = mysql_real_escape_string($_POST['postcode']);
$ethnic = mysql_real_escape_string($_POST['ethnic']);
$occupation = mysql_real_escape_string($_POST['occupation']);

$user = getIDCookie();
$id = $user['id'];

# Insert answer into DB
$q  = "UPDATE users SET username='$username',twitter='$twitter',email='$email',gender='$gender',age='$age',postcode='$postcode',ethnic='$ethnic',occupation='$occupation' WHERE id=$id";
mysql_query($q);

?>
