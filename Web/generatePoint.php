<?php 
  require_once('common.php');

  $lzw = new LZW();
  connect();
  $p = rand(0,1);
  if($p == 0) {
    $result = query('SELECT id,lat,lon FROM points WHERE fake=1 ORDER BY RAND() LIMIT 1;');
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
      echo base64_encode(gzencode($line['lat'].','.$line['lon'].','.$line['id'].',1'));
    }
  }
  else {
    $result = query('SELECT id,lat,lon FROM points WHERE fake IS NULL ORDER BY RAND() LIMIT 1;');
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
      echo base64_encode(gzencode($line['lat'].','.$line['lon'].','.$line['id'].',0'));
    }
  }
?>
