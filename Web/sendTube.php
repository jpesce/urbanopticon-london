<?php
require_once('common.php');
connect();

function distance($lat1, $lon1, $lat2, $lon2) {
  if($lat1 == $lat2 && $lon1 == $lon2) return 0;

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;

  return ($miles * 1.609344); #Km
}

$points_id = mysql_real_escape_string($_POST['point_id']);
$station_name = mysql_real_escape_string(URLdecode($_POST['answer_input']));


# Get answer info
$q = "SELECT tfl_id,lat,lon FROM tubes WHERE name='$station_name'";
$result = query($q);
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
  $tfl_answer = $line['tfl_id'];
  $answer_lat = $line['lat'];
  $answer_lon = $line['lon'];
}
if ($station_name=='dunno') $tfl_answer = 0;

# Insert answer into DB
$userinfo = getIDCookie();
$users_id = $userinfo['id'];
$q  = "INSERT INTO answers (points_id, tfl_answer, users_id) VALUES ($points_id, $tfl_answer, $users_id)";
mysql_query($q);


# Get correct answer
$q = "SELECT name,tubes_tfl_id,points.lat,points.lon FROM points JOIN tubes ON tubes.tfl_id=points.tubes_tfl_id WHERE id=$points_id";
$result = query($q);
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
  $correct_answer = $line['tubes_tfl_id'];
  $correct_name = $line['name'];
  $point_lat = $line['lat'];
  $point_lon = $line['lon'];
}

# Return result
if ($station_name=='dunno') {
  echo "15|info|<p><strong>15 points</strong> It was in $correct_name. Next one.</p>";
} 
else if($tfl_answer==$correct_answer) {
  echo "100|success|<p><strong>100 points</strong> That's correct!</p>";
} 
else {
  $d = distance($point_lat,$point_lon,$answer_lat,$answer_lon) * 100; #meters

  if($d <= 300) {
    $score = round(100/pow($d,0.05));
    $message = "<p><strong>$score points -</strong> It was in $correct_name: not quite right, but close.</p>";
  }
  else {
    if($d <= 5000)
      $score = round(75/pow($d-300,0.2));
    else $score = 0;
    $message = "<p><strong>$score points -</strong> It was in $correct_name.</p>";
  }

  echo "$score|error|$message";
}
?>
