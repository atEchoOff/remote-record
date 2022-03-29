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
</head>

<body>
  <!-- Navbar -->
  <?php Builder::navbar(); ?>

  <!-- Script to make elements draggable -->
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
        elmnt.style.marginLeft = (parseInt(elmnt.style.marginLeft.replaceAll("px", "")) + -pos1 + "px");
        console.log(elmnt.style.marginLeft);
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
        <a class="btn btn-dark" href="?command=record">Switch to Recording Mode</a>
      </div>
    </div>
    <div style="clear:both"></div>
    <!-- Editor Box -->
    <div class="composition-edit-box">
      <!-- Editor header -->
      <div class="box-header">
        <div class="btn-toolbar box-title" role="toolbar">
          <div class="btn-group mr-2" role="group" aria-label="First group" style="margin-right:4px;">
            <a href="#">
              <img src="images/PlaySymbol.png" class="circular-button" alt="Play">
            </a>
          </div>
          <div class="btn-group mr-2" role="group" aria-label="Second group" style="margin-right:4px;">
            <a href="#">
              <img src="images/hand.png" class="circular-button" alt="Drag">
            </a>
          </div>
          <div class="btn-group mr-2" role="group" aria-label="Third group">
            <a href="#">
              <img src="images/cut.png" class="circular-button" alt="Cut">
            </a>
          </div>
        </div>

        <div class="btn-toolbar box-buttons" role="toolbar" aria-label="Toolbar with button groups">
          <div class="btn-group mr-2" role="group" aria-label="First group" style="margin-right:4px;">
            <button type="button" class="btn btn-success" style="top:5px;">Save</button>
          </div>
        </div>
        <div style="clear:both"></div>
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

    <h2 class="box-title">Saved Recordings</h2>
    <div style="clear:both"></div>
    <div class="recording-section">
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

  <!-- Script to play audio, plays playableWaveform with given waveform object and image ID -->
  <script>
    function togglePlay(waveplayer, imgID) {
      if (waveplayer.isPlaying()) {
        document.getElementById(imgID).src = "images/PlaySymbol.png";
      } else {
        document.getElementById(imgID).src = "images/PauseSymbol.webp";
      }
      waveplayer.playPause();
    }
  </script>
</body>

</html>