<?php
    session_start();
    require_once "config.php";
    if(!isset($_SESSION['loggedin'])) {
        header('LOCATION:index.php'); die();
    }   
?>

  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
  <html>
  <head>
  <title>Producer Feeds</title>
  
  <link rel="stylesheet" href="./style/jquery-ui.css">
  <link rel="stylesheet" href="./style/style.css">
  <script src="./script/jquery-1.12.4.js"></script>
  <script src="./script/jquery-ui.js"></script>
  <script src="./script/sldp-v2.17.4_sdk_84be7116.min.js" type="text/javascript"></script>

    <?php     
      //Fetch streams that this user has access to                                                         
      $result = $mysqli->query("SELECT * FROM streams WHERE project='" . $_SESSION["project"] . "' AND active='1' ORDER BY sid DESC");

      //Initialize array variable
      $dbdata = array();

      //Fetch into associative array
      while ( $row = $result->fetch_assoc())  {
          $dbdata[]=$row;
      }
      // Pull audio feeds
      $result3 = $mysqli->query("SELECT * FROM audiostreams WHERE project='" . $_SESSION["project"] . "' AND active='1' ORDER BY sid DESC");

      //Initialize array variable
      $dbaudio = array();
      
      //Fetch into associative array
      while ( $row = $result3->fetch_assoc())  {
          $dbaudio[]=$row;
      }
      

      if(isset($_SESSION['admin'])) {
        $result2 = $mysqli->query("SELECT * FROM projects");


        //Initialize array variable
        $dbprojects = array();
        
        //Fetch into associative array
        while ( $row = $result2->fetch_assoc())  {
            $dbprojects[]=$row;
        }
      }   
           
    ?>   
  
  <script>
    var oStreams = <?=json_encode($dbdata, JSON_UNESCAPED_SLASHES)?>;
    var oAudioStreams = <?=json_encode($dbaudio, JSON_UNESCAPED_SLASHES)?>;
    var iBuffer = 3000; 

    <?php
      if(isset($_SESSION['buffer'])) {
          echo 'var iBuffer = ' . $_SESSION['buffer'] . ';';
      }

      if(isset($_SESSION['admin'])) {
        echo 'var oProjects = ' . json_encode($dbprojects, JSON_UNESCAPED_SLASHES) . ';';
      }
    ?> 

    var oButtons = [];
    var sldpPlayer = null;
    var sldpAudioPlayer = null;
    
    document.addEventListener('DOMContentLoaded', initPage);

    function initPage() {

    <?php
      if(isset($_SESSION['admin'])) {
    ?>
          if(typeof oProjects != "undefined") {
            $('#projectdd').on('change', function() {                    
              console.log("User: " + $(this).attr('user') + " Val: " + this.value );
              $.ajax({
                  type: "POST",
                  url: 'update.php',
                  data: {update:'users',uid:$(this).attr('user'),project:this.value},
                      success: function(data){
                      location.reload();
                  },
                  error: function(xhr, status, error){
                      console.error(xhr);
                  }
              });
            });
          }
          


    <?php
      }
    ?>
    $('html').click(function() {
        if($("#menuPopup").is(":visible")) { $("#menuPopup").toggle(); }
    });

    $('#menuPopup').click(function(event){
        event.stopPropagation();
    });

    $("#menuToggle").click(function() { $("#menuPopup").toggle("fade"); });

    $("#latencySlider").slider({
            range:false,
            min: 100,
            max: 5000,
            step:100,
            slide: function(event, ui) {
                $("#latencyValue").val(ui.value/1000);
                iBuffer = ui.value;
            },
            change: function(event, ui) {
              sldpPlayer = buildPlayer(sldpPlayer, $("#videoSelector").val(), false, 'stream', false, iBuffer);
              
              if(sldpAudioPlayer) {
                sldpAudioPlayer = buildPlayer(sldpAudioPlayer, $("#audioSelector").val(), false, 'audioStream', true, iBuffer);
              }

              $.ajax({
                  type: "POST",
                  url: 'update.php',
                  data: {update:'buffer',uid:$("#projectdd").attr('user'),buffer:iBuffer},
                      success: function(data){
                  },
                  error: function(xhr, status, error){
                      console.error(xhr);
                  }
              });

            },
        }).each(function() {


    var opt = $(this).data().uiSlider.options;

    // Get the number of possible values
    var vals = 6;
    // Position the labels
    for (var i = 0; i <= vals; i++) {

        // Create a new element and position it with percentages
        if(i == 0) {
          var el = $('<label class="sliderLabel">0</label>').css('left', (i/vals*100) + '%'); 


        }
        else {
          var el = $('<label class="sliderLabel">' + (((i*1000 + opt.min)-100)/1000) + '</label>').css('left', (((i/vals*100))-1.5) + '%');
        }
        
        // Add the element inside #slider
        $("#latencySlider").append(el);

  }

});

    // set initial buffer
    $("#latencySlider").slider('value', iBuffer);

    $("#latencyValue").val($("#latencySlider").slider("value")/1000);

    $('#latencyValue').on('input', function() {
      $("#latencySlider").slider('value', $(this).val()*1000);
    });
    
    if(oStreams.length > 0) {
      sldpPlayer = buildPlayer(sldpPlayer, oStreams[0].url, true, 'stream', false, 500);
        var s = $('<select />', {id:"videoSelector"});

        for(let i = 0; i < oStreams.length; i++) {
            $('<option />', {value: oStreams[i].url, text: oStreams[i].title}).appendTo(s);
        }

        s.appendTo('#buttonContainer');

        $(s).change(function(){
          sldpPlayer.setStreamURL($("#videoSelector").val());
        });
              

      }



    
      if($("#admin")) {
          $( "#admin" ).click( function( event ) {
              window.location.replace("./users.php");
          });
      }
      
      $("#logout").click( function( event ) {
        window.location.replace("./logout.php");
      });

// IF AUDIO ONLY STREAMS EXIST FOR THE PROJECT, BUILD THE SELECTOR AND CONTROLS
      if(oAudioStreams.length > 0) {
        $('<div />', {id: "audioStream"}).appendTo("#buttonContainer");
        sldpAudioPlayer = buildPlayer(sldpAudioPlayer, oAudioStreams[0].url, true, 'audioStream', true, 500);

        var as = $('<select />', {id:"audioSelector"});
          

          for(let i = 0; i < oAudioStreams.length; i++) {
             $('<option />', {value: oAudioStreams[i].url, text: oAudioStreams[i].title}).appendTo(as);
          }

          as.appendTo('#buttonContainer');

          $(as).change(function(){
            sldpAudioPlayer.setStreamURL($("#audioSelector").val());
          });
          
      }

    }

    function buildPlayer(oSldp, sUrl, bMuted, sParentDiv, bAudioOnly, iLatency) {
      if(oSldp) { oSldp.destroy(); }

        oSldp = SLDP.init({
          container: sParentDiv,
          stream_url: sUrl,
          height: 'parent',
          muted:true,
          adaptive_bitrate:true,
          audio_only:bAudioOnly,
          buffering:iLatency,
          autoplay: true
        })

      return oSldp;
    }

  </script>
  
  </head>
  
<body style="background-color:#000000">

<img id="vir-bg" class="center opacity80" src="./images/vir-bg.png">
<div class="center"><img id="vir-sml-img" class="center" src="./images/virtualis.png"></div>

<div id="buttonContainer" class="center" style="margin-bottom:20px">
</div>

<div id="tools">
<div id="menuToggle"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
  <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
</svg></div>
<div id="menuPopup">
  <ul>
    <li class="spacer"><div id="userLine"><?php echo $_SESSION['username']; ?></div></li>
<?php
    if(isset($_SESSION['admin'])) {     
        echo '<a href="./admin.php"><li>Admin</li></a>';
      }
  ?>
  <li>Latency (seconds)
  <input type="text" id="latencyValue">
        <div class="spacer"></div>
        <div id="latencySlider"></div>
  </li>  
  <a href="./logout.php"><li>Logout</li></a>
    </ul>
</div>
</div>
<?php
if(isset($_SESSION['admin'])) {
     
echo '<div id="projectChooser"><select id="projectdd" name="projectdd" user="'. $_SESSION['uid'] .'">';
        foreach($dbprojects as $project) {
            if($project['pid'] == $_SESSION['project']) { 
                echo '<option user="'. $_SESSION['uid'] .'" value="' . $project["pid"] . '" selected>' . $project['ptitle'] . '</option>';
            }
            else {
                echo '<option user="'. $_SESSION['uid'] .'" value="' . $project["pid"] . '">' . $project['ptitle'] . '</option>';
            }

        }
        echo '</select></div>';
}
?>

<div id="stream" class="center"></div>

  </body>
</html>
