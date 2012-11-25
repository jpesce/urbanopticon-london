$.ajaxSetup ({
  cache: false
});
var loadingHtml = "<div class='loading'><img src='images/loading.gif' alt='loading...' /></div>";

$(function(){
  modal();
  generatePoint();

  // Popovers
  showPopover("#choose-tube", "<p>Guess the tube stations close to this picture. The closer, the more points (max 100).</p>");
  showPopover("#choose-area", "<p>Guess the borough in which this picture was taken and earn 50 points. Yet, if you at least correctly guess the area (e.g., south, north), you will earn 25 points</p>");
  showPopover("#choose-dunno", "<p>Skip to the next picture and yet earn 15 points for not answering wrongly.</p>");

  //Twipsy
  showTwipsy("#report", "If the image is recognizable from visible street signs please report it")

  //Report button
  $("#report").click(function(){              
    var point_id = $('#point-id').val();
    var dataString = 'point_id='+point_id;
    $.ajax({
       type: "POST",
       url: 'report.php',
       data: dataString,
       success: function(response) { 
         alertMessage('alert', 'Thanks for reporting. We will look into it as soon as possible.');
       }
    }); // End AJAX
  });

  // Choose precision
  $("#choose-tube").click(function(){
    $("#answer").html(loadingHtml).load("tubeSearch.php", function(){
      loadAnswerForm();
    });
  });

  $("#choose-area").click(function(){
    $("#answer").html(loadingHtml).load("areaSearch.php", function(){
      loadAnswerForm();
    });
  });

  $("#choose-dunno").click(function(){
    var point_id = $('#point-id').val();
    var station_name = 'dunno';

    submit(point_id,station_name,'sendTube.php');
  });


}); 

function hide(id, e){ 
  var target = (e && e.target) || (event && event.srcElement); 
  var obj = document.getElementById(id); 
  checkParent(id, target)?obj.style.display='none':null; 
}
 
function checkParent(id, t){ 
  while(t.parentNode){ 
    if(t==document.getElementById(id)){ 
      return false 
    } 
    t=t.parentNode 
  } 
  return true 
} 

function choice(str)
{
  $("#answer-input").val(str); 
  $("#livesearch").css('display', 'none');
}

function modal(header,body){
  $(".modal-header h3").html(header);
  $(".modal-body").html(body);
  $('#modal').modal({backdrop: 'static', keyboard: false});
}

function alertMessage(type, message){
  if($('#alert').length != 0){
    $("#alert").remove();
    alertHtml = "<div id='alert' class='alert-message " + type + " fade in' data-alert='alert'><a class='close' href='#'>×</a><p> " + message + " </p></div>";
    $(alertHtml).appendTo('#top-error').delay(3000).fadeOut(300, function() { $(this).remove(); });
  } else {
    alertHtml = "<div id='alert' class='alert-message " + type + " fade in' data-alert='alert'><a class='close' href='#'>×</a><p> " + message + " </p></div>";
    $(alertHtml).appendTo('#top-error').delay(3000).fadeOut(300, function() { $(this).remove(); });
  }
}

function increase(div, i) {
  score = parseInt($(div).html());
  score += parseInt(i);
  $(div).html(score);
  
}

function submit(point_id,answer_input,page) {
  var dataString = 'point_id='+point_id+'&answer_input='+escape(answer_input);
  type = $("#type").val();
   
  // Validate
  $.get('checkAnswer.php?q='+escape(answer_input)+'&t='+encodeURI(type), function(data){
    if(answer_input != 'dunno' && data=='0') {
      alertMessage('error', '<strong>Invalid answer!</strong> Try selecting one from the dropdown list')
      return;
    }
  
    
    $.ajax({
  
      type: "POST",
      url: page,
      data: dataString,
      success: function(response) {
        //alertMessage('error', response);
        response = response.split('|');
        var score_up = response[0];
        var type = response[1];
        var message = response[2];
        alertMessage(type, message);

        increase('#score', score_up);
        increase('#progress', 1);

        // End game?
        if(parseInt($("#progress").html()) >= 10) {
          modal("Score","");
          $(".modal-content").load('share.php?s='+$('#score').html(), function(){
            // Submit user form
            $("#user-form").submit(function() {
              var dataString = $(this).serialize();
              $.ajax({
  
                type: "POST",
                url: 'sendUser.php',
                data: dataString,
                success: function(response) { 
                }

              }); // End AJAX
              $('#modal').modal('hide');       
              return false;
            });
          });

          $('#score').html('0');
          $('#progress').html('0');
        }

        // New point
        $('#station-name').val('');
        generatePoint();
      }
    });
  });
}

function loadAnswerForm() {
  // Help fill station name
  $("#answer-input").keyup(function(){
    value = $("#answer-input").val();
    type = $("#type").val();
    var url = "livesearch.php?q="+encodeURI(value)+"&t="+encodeURI(type);
    $("#livesearch").load(url);
    $("#livesearch").css('display','block');
  });

  $(document).click(function(){
    if($('#livesearch').length != 0) {
      hide('livesearch');
    }
  });

  // Submit answer
  $("#submit").click(function(){
    var point_id = $('#point-id').val();
    var answer = $('#answer-input').val();

    
    if($("#type").val()=='tubes')
      submit(point_id,answer,'sendTube.php');
    else if($("#type").val()=='boroughs')
      submit(point_id,answer,'sendBorough.php');
  });
}

function generatePoint() {
  $.get('generatePoint.php', function(data){
    $("#answer").html(loadingHtml).load('helper.php');
    var value = JXG.getValue(data);
    var values = value.split(',');
    $("#image-file").attr("src","photos/"+values[2]+".jpg");
    $("#point-id").val(values[2]);
    $("#fake").val(values[3]);
    var values = new google.maps.LatLng(values[0],values[1]);
    var options = { 
      position: values,
      pov: {
        heading: 20, 
        pitch: 10, 
        zoom: 0,
      },  
      scrollwheel: false,
      linksControl: false,
      addressControl: false,
      zoomControl: false,
      disableDoubleClickZoom: true
    };  
    var values = new google.maps.StreetViewPanorama(document.getElementById("pano"),options);
    map.setStreetView(values);
    });
}

function showTwipsy(id, title) {
  $(id).twipsy({
    title: function(){ return title; },
    placement: 'right',
    offset: 10
  });
}

function showPopover(id, content) {
  $(id).popover({
    html: true,
    content: function(){ return content; },
    placement: 'below',
    offset: 10
  });
}
