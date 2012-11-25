<?php
require_once('common.php');
connect();

function sameQuadrant($b1, $b2) {
  # Regions are hardcoded :(
  $quadrants = array('N' => array('00AC',
                                  '00AK',
                                  '00AP',
                                  '00BH'),

                     'W' => array('00AN',
                                  '00AT',
                                  '00AJ',
                                  '00AS',
                                  '00AQ',
                                  '00AE'),

                     'C' => array('00AG',
                                  '00AU',
                                  '00BK',
                                  '00AW',
                                  '00BJ',
                                  '00AY',
                                  '00BE'),

                     'S' => array('00AF',
                                  '00AH',
                                  '00BF',
                                  '00BA',
                                  '00AX',
                                  '00BD'),

                     'E' => array('00BC',
                                  '00AB',
                                  '00AR',
                                  '00AM',
                                  '00AA',
                                  '00BG',
                                  '00BB',
                                  '00AZ',
                                  '00AL',
                                  '00AD'));

  foreach($quadrants as $quadrant) {
    if(in_array($b1,$quadrant) and in_array($b2,$quadrant)) {
      return 1;
    }
  }
  return 0;
  
}

$points_id = mysql_real_escape_string($_POST['point_id']);
$station_name = mysql_real_escape_string(URLdecode($_POST['answer_input']));

# Get answer info
$q = "SELECT ons_label,lat,lon FROM boroughs WHERE name='$station_name'";
$result = query($q);
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
  $answer = $line['ons_label'];
  $answer_lat = $line['lat'];
  $answer_lon = $line['lon'];
}

# Insert answer into DB
$userinfo = getIDCookie();
$users_id = $userinfo['id'];
$q  = "INSERT INTO answers (points_id, boroughs_answer,users_id) VALUES ($points_id, '$answer', $users_id)";
mysql_query($q);


# Get correct answer
$q = "SELECT name,boroughs_ons_label,points.lat,points.lon FROM points JOIN boroughs ON boroughs.ons_label=points.boroughs_ons_label WHERE id=$points_id";
$result = query($q);
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
  $correct_answer = $line['boroughs_ons_label'];
  $correct_name = $line['name'];
  $point_lat = $line['lat'];
  $point_lon = $line['lon'];
}

# Return result
if ($station_name=='dunno') {
  echo "15|info|<p><strong>15 points</strong> It was in $correct_name. Next one.</p>";
} 
else if($answer==$correct_answer) {
  echo "50|success|<p><strong>50 points</strong> That's correct!</p>";
} 
else {
  if(sameQuadrant($correct_answer,$answer)) {
    $score = 25;
    $message = "<p><strong>$score points -</strong> It was in $correct_name. The borough isn't quite right, but the area is.</p>";
  }
  else {
    $score = 0;
    $message = "<p><strong>$score points -</strong> It was in $correct_name.</p>";
  }

  echo "$score|error|$message";
}
?>
