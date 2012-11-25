<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Psychological Map</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le styles -->
    <!--link href="css/bootstrap.css" rel="stylesheet"-->
    <link rel="stylesheet/less" type="text/css" href="less/main.less">
    <script src="js/less-1.1.5.min.js" type="text/javascript"></script>

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
  </head>

  <body>   
    <div id="station-container">
      <div class="page-header">
        <h1>Station Names</h1>
      </div>
      <ul id="station-names">
      <?php
      require('common.php');
      
      connect();
      $result = query("SELECT name FROM tubes ORDER BY name");
      while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        echo '<li ';
        echo 'onclick="choice(\''.$line['name'].'\')">';
        echo $line['name'];
        echo '</li>';
      }
      ?>
      <div style="clear:both;"></div>
      </ul>
    </div>
  </body>
</html>
