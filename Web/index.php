<?php 
require_once('common.php');
getID();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta property="fb:app_id" content="297302786992554" />
    <title>UrbanOpticon - How well do you know London?</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le styles -->
    <!--link href="css/bootstrap.css" rel="stylesheet"-->
    <link rel="stylesheet/less" type="text/css" href="less/main.less">
    <script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>
    <script src="js/less-1.1.5.min.js" type="text/javascript"></script>
    <script src="js/bootstrap-modal.js" type="text/javascript"></script>
    <script src="js/bootstrap-alerts.js" type="text/javascript"></script>
    <script src="js/bootstrap-twipsy.js" type="text/javascript"></script>
    <script src="js/bootstrap-popover.js" type="text/javascript"></script>
    <script src="js/jsxcompressor.js" type="text/javascript"></script>
    <script src="js/search.js" type="text/javascript"></script>
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
  </head>

  <body>   
    
    <!-- Le modal window -->
    <div id="modal" class="modal fade hide">
      <div class="modal-header">
        <h3></h3>
      </div>
      <div class="modal-content">
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <a href="javascript:$('#modal').modal('hide');" class="btn secondary">Ok</a>
        </div>
      </div>
    </div>

    <!-- Le errors -->
    <div id="top-error">
    </div>


    <!-- Le menu -->
    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="<?php echo $BASE_URL;?>">urban<strong>opticon</strong></a>
        </div>
      </div>
    </div>

    <!-- Le content -->
    <div class="container">
      <div class="right">
      <img src="images/cuni.png" alt="University of Cambridge" height="30px"/>
      &nbsp;&nbsp;
      <img src="images/ufmg.png" alt="UFMG" height="30px"/>
      </div>
      
        <div class="addthis_toolbox addthis_default_style "
             addthis:url="http://www.urbanopticon.org/">
        <a class="addthis_button_facebook_like"
           fb:like:layout="button_count"></a>
        <a class="addthis_button_tweet" tw:via="urbanopticon"></a>
        <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
        </div>
        <script type="text/javascript"
        src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4eef554136c9e81b"></script>
        </script>
 
      <div class="page-header">
        <div id="game-stats">
          <ul>
            <li><strong>Score:</strong> <span id="score">0</span></li>
            <li><strong>Progress:</strong> <span id="progress">0</span>/10</li>
          </ul>
        </div>
        <h1>Where is this?</h1>
      </div>

      <p class="left">Choose Your Answer's Precision:</p>
      <div id="choose_type">
        <div id="choose-tube" class="btn">
          Tube Station
        </div>
        <div id="choose-area" class="btn">
          Borough
        </div>
        <div id="choose-dunno" class="btn">
          Don't know
        </div>
      </div>


      <div id="picture">
        <div id="pano"></div>
        <div id="google-copyright"><a href="http://maps.google.com"></a></div>
        <input type="hidden" id="point-id" name="point_id" value="" />
        <input type="hidden" id="fake" name="fake" value="" />
        <div id="report">report image</div>
      </div>

      <div id="answer">
      </div>

      <div class="clear"></div>
        
      <div class="row">
        <div class="span8">
          <div class="page-header"><h2>Why playing it?</h2></div>
          <p class="justify">The game is designed for purposes beyond pure entertainment - your answers will contribute to promote urban interventions where needed. This site extracts Londoners' mental images of the city. By testing which places are remarkable and unmistakable and which places represent faceless sprawl, we are able to draw the recognisability map of London. </p>
        </div>
        <div class="span8">
          <div class="page-header"><h2>Why recognisability matters?</h2></div>
          <p class="justify">Every Londoner has had long associations with some parts of the city, which bring to mind a flood of associations. Over the years, London has been built and maintained in a way that it is imaginable: in a way that mental maps of the city are clear and economical of mental effort. Starting from Kevin Lynch's seminal book "The image of the city", studies have posited that good imaginability allows city dwellers to feel at home and increase their community well-being. The good news is that the concept of imaginability is quantifiable, and it is so partly based on recognisability maps. We hope that these maps will inform the positive design of public facilities (e.g., civic buildings) and promote urban interventions (e.g., place landmark in key areas, refurbish memorable horrible buildings).</p>
        </div>
      </div>
      <footer>
      <div id="team">
      <p><strong>Team: </strong> 
         <a href="mailto:jpesce@gmail.com" target="_blank">João Pesce</a> (UFMG), 
         <a href="http://www.cl.cam.ac.uk/~dq209/" target="_blank">Daniele Quercia</a> (University of Cambridge), 
         <a href="http://homepages.dcc.ufmg.br/~virgilio/site/" target="_blank">Virgílio Almeida</a> (UFMG) and  
         <a href="http://www.cl.cam.ac.uk/~jac22/" target="_blank">Jon Crowcroft</a> (University of Cambridge)
         <br/>
         <strong>Contact: </strong>
         <a href="mailto:dq209@cl.cam.ac.uk?cc=jpesce@gmail.com" target="_blank">Daniele Quercia</a>
      </p>
      </div>
      </footer>

    </div> 
    <!--/form-->

  </body>
</html>

