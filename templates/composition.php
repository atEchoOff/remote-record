<!-- Home Page -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Jimmy Connors and Brian Christner">
  <title>Composition Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="styles/main.css">
  <!-- This needs to be placed at the top to function correctly -->
  <script src="https://unpkg.com/wavesurfer.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
  <!-- Navbar -->
  <?php Builder::navbar(); ?>

  <!-- Script to make elements draggable -->
  <!-- https://www.w3schools.com/howto/howto_js_draggable.asp -->
  <script>
    function dragElement(elmnt) {
      var pos1 = 0,
        pos3 = 0
      if (document.getElementById(elmnt.id + "header")) {
        // if present, the header is where you move the DIV from:
        document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
      } else {
        // otherwise, move the DIV from anywhere inside the DIV:
        elmnt.onmousedown = dragMouseDown;
      }

      function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
      }

      function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos3 = e.clientX;
        // set the element's new position:
        elmnt.style.marginLeft = (parseFloat(elmnt.style.marginLeft.replaceAll("px", "")) + -pos1 + "px");
      }

      function closeDragElement() {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
      }
    }
  </script>

  <!-- Composition Box -->
  <div class="box-section">
    <h2 class="box-title">Composition</h2>
    <div class="btn-toolbar box-buttons" role="toolbar" aria-label="Toolbar with button groups">
      <div class="btn-group mr-2" role="group" aria-label="First group">

        <!-- Go to recording page for this specific composition -->
        <a class="btn btn-dark" href="?command=record&composition=<?php echo $composition["name"]; ?>">Switch to Recording Mode</a>
      </div>
    </div>
    <div style="clear:both"></div>
    <!-- Editor Box -->
    <div class="composition-edit-box">
      <!-- Editor header -->
      <div class="box-header">
        <div class="btn-toolbar box-title" role="toolbar">
          <div class="btn-group mr-2" role="group" aria-label="First group" style="margin-right:4px;">
            <!-- Query server to stitch together audio given parameters in edit panel -->
            <a onclick="stitchAudio()">
              <img src="images/PlaySymbol.png" class="circular-button" alt="Play">
            </a>
          </div>
          <div class="btn-group mr-2" role="group" aria-label="Second group" style="margin-right:4px;">
            <!-- Zoom in edit panel -->
            <a onclick="zoomIn()">
              <img src="images/zoomin.png" class="circular-button" alt="Drag">
            </a>
          </div>
          <div class="btn-group mr-2" role="group" aria-label="Third group">
            <!-- Zoom out edit panel -->
            <a onclick="zoomOut()">
              <img src="images/zoomout.png" class="circular-button" alt="Cut">
            </a>
          </div>
        </div>

        <!-- Save button -->
        <div class="btn-toolbar box-buttons" role="toolbar" aria-label="Toolbar with button groups">
          <div class="btn-group mr-2" role="group" aria-label="First group" style="margin-right:4px;">
            <button type="button" class="btn btn-success" style="top:5px;">Save</button>
          </div>
        </div>
        <div style="clear:both"></div>
      </div>


      <!-- Will contain audio after queried to stitch together edit panel -->
      <div class="box-header" style="display:none;" id="exampletrack">
      </div>
      <div class="recording-section">
        <?php
        if (sizeof($recordings) === 0) {
          echo "<h5>No recordings</h5>";
        }
        foreach ($recordings as $recording) {
          Builder::playableWaveform($recording["location"], $recording["author_name"] . " - " . $recording["name"], $recording["id"], $delete = true, $drag = true);
        }
        ?>
      </div>
    </div>

    <!-- Area for saved audio -->
    <!-- Note, this is currently still just a (broken) template -->
    <h2 class="box-title">Saved Recordings</h2>
    <div style="clear:both"></div>
    <div class="recording-section" id="recordingsection">
      <!-- Recording template with download button -->
      <div class="recording-box" style="width:800px;">
        <!-- Note: width and left will be set by javascript case-by-case once we have actual recordings-->
        <div class="recording-box-panel">
          <a href="#">
            <img src="images/PlaySymbol.png" class="circular-button" alt="Play">
          </a>
        </div>
        <div class="recording-waveform-end">
          <div class="recording-box-label">
            <p>With Basses</p>
          </div>
          <div class="recording-waveform">
            <img src="WaveForm.webp" alt="Waveform">
          </div>
        </div>
        <a href="#">
          <img src="images/download.png" class="download-button" alt="Download">
        </a>
        <a href="#">
          <img src="images/delete.png" class="delete-button" alt="Delete">
        </a>
      </div>
      <div style="clear:both"></div>
      <!-- End of Template -->
      <!-- Recording template with download button -->
      <div class="recording-box" style="width:800px;">
        <!-- Note: width and left will be set by javascript case-by-case once we have actual recordings-->
        <div class="recording-box-panel">
          <a href="#">
            <img src="images/PlaySymbol.png" class="circular-button" alt="Play">
          </a>
        </div>
        <div class="recording-waveform-end">
          <div class="recording-box-label">
            <p>Without Basses</p>
          </div>
          <div class="recording-waveform">
            <img src="WaveForm.webp" alt="Waveform">
          </div>
        </div>
        <a href="#">
          <img src="images/download.png" class="download-button" alt="Download">
        </a>
        <a href="#">
          <img src="images/delete.png" class="delete-button" alt="Delete">
        </a>
      </div>
      <div style="clear:both"></div>
      <!-- End of Template -->
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <!-- Script to play audio, swaps between play and pause icon -->
  <script>
    function togglePlay(waveplayer, imgID) {
      // This is taken out temporarily to stop a broken icon when the audio finishes playing
      // but is not paused
      // if (waveplayer.isPlaying()) {
      //   document.getElementById(imgID).src = "images/PlaySymbol.png";
      // } else {
      //   document.getElementById(imgID).src = "images/PauseSymbol.webp";
      // }
      // Toggle waveplayer
      waveplayer.playPause();
    }
  </script>

  <!-- Script to zoom in or out on edit panel -->
  <script>
    function zoomIn() {
      <?php
      // For each recording, parse the name of the recording box and zoom in dimensions
      foreach ($recordings as $recording) {
        $clean_location = Utils::cleanLocation($recording["location"]);
        echo "
        // get the box to stretch
        let box$clean_location = document.getElementById('recbox$clean_location');

        // scale the left margin and width
        box$clean_location.style.marginLeft = (box$clean_location.style.marginLeft.replaceAll('px', '')) * 2 + 'px';
        box$clean_location.style.width = (box$clean_location.style.width.replaceAll('px', '')) * 2 + 'px';

        // Reload the waveform
        $clean_location.load('" . $recording['location'] . "');
        ";
      }
      ?>
    }

    function zoomOut() {
      <?php
      // For each recording, parse the name of the recording box and zoom out dimensions
      foreach ($recordings as $recording) {
        $clean_location = Utils::cleanLocation($recording["location"]);
        echo "
        // get the box to stretch
        let box$clean_location = document.getElementById('recbox$clean_location');

        // scale the left margin and width
        box$clean_location.style.marginLeft = (box$clean_location.style.marginLeft.replaceAll('px', '')) / 2 + 'px';
        box$clean_location.style.width = (box$clean_location.style.width.replaceAll('px', '')) / 2 + 'px';

        // Reload the waveform
        $clean_location.load('" . $recording['location'] . "');
        ";
      }
      ?>
    }
  </script>

  <!-- Script to take the edit panel details and send to the server to stitch together -->
  <script>
    function stitchAudio() {
      // initialize margins string (holds the distance from the start as a comma delimited list)
      let margins = "";
      // initalize width as -1 to get the width of the first element
      let width = -1;
      <?php
      // Stores the ids for eaach element so it can be imploded into a string for javascript
      $ids = [];
      // for each recording, add the id to the php list
      // also, add the percent of the recording length distance from the start
      // to the margins comma seperated list
      foreach ($recordings as $recording) {
        $clean_location = Utils::cleanLocation($recording["location"]);
        array_push($ids, $recording["id"]);
        echo "
        if (width == -1) {
            width = parseFloat(document.getElementById('recbox$clean_location').style.width.replaceAll('px', ''));
        }
        margins += (parseFloat(document.getElementById('recbox$clean_location').style.marginLeft.replaceAll('px', '')) * 100) / width + ',';
        ";
      }
      // Put the ids list into javascript as a string
      echo "let ids = '" . implode(",", $ids) . "';";
      ?>

      // Remove the last comma from margins
      margins = margins.slice(0, -1);

      // Put loading there until waveform loads
      $('#exampletrack').html("<p>Loading... Please Wait");

      // reload example track area to show merged track after loading
      $('#exampletrack').load('?command=stitch_audio&ids=' + ids + '&margins=' + margins + "&zoom=" + 1 + "&composition=<?php echo $composition["name"]; ?>");

      // set area to visible
      document.getElementById("exampletrack").style.display = "block";

    }
  </script>


  <!-- Forces all canvas elements to have aria-label to make page accessible -->
  <script>
    let canvasElements = document.getElementsByTagName('canvas');
    for (var i = 0; i < canvasElements.length; i++) {
      canvasElements[i].ariaLabel = "Waveform graphic";
    }
  </script>
</body>

</html>