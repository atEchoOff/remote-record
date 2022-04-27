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
        let temp = parseFloat(elmnt.style.marginLeft.replaceAll("px", "")) + -pos1;
        if (temp < 0) {

          temp = 0;
        }
        elmnt.style.marginLeft = temp + "px";
      }

      function closeDragElement() {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
      }
    }
  </script>
  <!-- Script to make elements draggable by keypress for accessibility -->
  <script>
    function addKeyboardDrag(elmnt) {
      if (document.getElementById(elmnt.id + "header")) {
        // if present, the header is where you move the DIV from:
        document.getElementById(elmnt.id + "header").onkeydown = dragWithKey;
      } else {
        // otherwise, move the DIV from anywhere inside the DIV:
        elmnt.onkeydown = dragWithKey;
      }

      function dragKeyDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        currentKey = e.code;
        elmnt.onkeyup = closeDragKeyElement;
        // call a function whenever the cursor moves:
        elmnt.onkeydown = dragWithKey;
      }

      function dragWithKey(e) {
        e = e || window.event;
        //if()
        if(e.code == "ArrowLeft" || e.code =="ArrowRight"){
          e.preventDefault();
        }
        let temp = 0;
        if(e.code == "ArrowLeft"){
          temp = parseFloat(elmnt.style.marginLeft.replaceAll("px", "")) - 1;
        }else if(e.code == "ArrowRight"){
          temp = parseFloat(elmnt.style.marginLeft.replaceAll("px", "")) + 1;
        }
        // set the element's new position:
        if (e.code == "ArrowLeft" || e.code=="ArrowRight"){
          if (temp < 0) {
            temp = 0;
          }
          elmnt.style.marginLeft = temp + "px";
        }
      }

      function closeDragKeyElement() {
        // stop moving when mouse button is released:
        elmnt.onkeyup = null;
        elmnt.onkeydown = null;
      }
    }
       
  </script>

  <!-- Page title -->
  <h1>
    <?php echo $composition["name"] ?> Composer Page
  </h1>

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
            <?php
            // Only show merge button if there are recordings
            if (sizeof($recordings) !== 0) {
            ?>
              <!-- Query server to stitch together audio given parameters in edit panel -->
              <button class="hidden-button" onclick="stitchAudio()">
                <img src="images/merge.png" class="circular-button" alt="Play">
              </button>
            <?php
            }
            ?>
          </div>
          <div class="btn-group mr-2" role="group" aria-label="Second group" style="margin-right:4px;">
            <!-- Zoom in edit panel -->
            <button class="hidden-button" onclick="zoomIn()">
              <img src="images/zoomin.svg" class="circular-button" alt="Drag">
          </button>
          </div>
          <div class="btn-group mr-2" role="group" aria-label="Third group">
            <!-- Zoom out edit panel -->
            <button class="hidden-button" onclick="zoomOut()">
              <img src="images/zoomout.svg" class="circular-button" alt="Cut">
          </button>
          </div>
        </div>

        <!-- Save button -->
        <div class="btn-toolbar box-buttons" role="toolbar" aria-label="Toolbar with button groups">
          <div class="btn-group mr-2" role="group" aria-label="First group" style="margin-right:4px;">
            <input type="text" class="form-control" placeholder="Product Name" style="margin-top:5px;width:300px;" id="name" name="name" required />
            <button <?php
                    if (sizeof($recordings) === 0) {
                      echo "disabled";
                    }
                    ?> onclick="saveMergedAudio()" class="btn btn-success" style="margin-top:5px;">Save</button>
          </div>
        </div>
        <div style="clear:both"></div>
      </div>
      <!-- Will contain audio after queried to stitch together edit panel -->
      <!-- Scrolls using javascript -->
      <div class="box-header" style="display:none; padding-left:0px; background-color:white; width:100%; overflow: hidden;" id="exampletrack">
      </div>
      <div class="recording-section">
        <?php
        if (sizeof($recordings) === 0) {
          echo "<h5>&emsp;No recordings</h5>";
        }
        foreach ($recordings as $recording) {
          Builder::playableWaveform($recording["location"], $recording["author_name"] . " - " . $recording["name"], $recording["id"], $delete = true, $drag = true);
        }
        ?>
      </div>
    </div>

  </div>
  <div class="box-section">

    <!-- Area for saved audio -->
    <!-- Note, this is currently still just a (broken) template -->
    <div class="row">
      <h2 class="box-title">Saved Recordings</h2>
    </div>

    <div style="overflow:auto;">
      <!-- For each product, show an existing waveform -->
      <?php
      // If there are no products, print a message for it
      if (sizeof($products) === 0) {
        echo "<h5>There are no saved products</h5>";
      }

      // Print all product waveforms
      foreach ($products as $product) {
        echo Builder::playableWaveform($product["location"], $product["name"], $product["id"], true, false, $product["width"], true);
      }
      ?>
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
    // Function to get the ids and margins inside the edit panel
    function getIDsAndMargins() {
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

      return [ids, margins, width / 1024];
    }

    function stitchAudio() {
      // Get the ids and margins form thlet ids = result[0];
      let result = getIDsAndMargins();
      let ids = result[0];
      let margins = result[1];
      let zoom_ratio = result[2];

      // Put loading there until waveform loads
      $('#exampletrack').html("<p>&emsp;Loading... Please Wait");

      // reload example track area to show merged track after loading
      // Get rid of spaces in composition name
      // When complete, adjust the zoom
      $('#exampletrack').load('?command=stitch_audio&ids=' + ids + '&margins=' + margins + "&zoom=" + 1 + "&composition=<?php echo str_replace(" ", "%20", $composition["name"]); ?>", function() {
        // Get new element
        let new_wave = $("#recboxtempfilesslash<?php echo $user['id']; ?>");

        // Multiply width of result by the zoom ratio
        new_wave.width(new_wave.width() * zoom_ratio);

        // Reset the margins
        new_wave.css("margin-left", -$('.recording-section').get(0).scrollLeft);
      });

      // set area to visible
      document.getElementById("exampletrack").style.display = "block";




    }
  </script>

  <!-- Script to save the merged audio permanently -->
  <script>
    function saveMergedAudio() {
      // Get ids and margins
      let result = getIDsAndMargins();
      let ids = result[0];
      let margins = result[1];

      // Get the name from the name field
      let name = $("#name").val();

      // Load the page to save the data
      window.location.href = "?command=save_merge&ids=" + ids + "&margins=" + margins + "&composition=<?php echo $composition["name"]; ?>&name=" + name;
    }
  </script>


  <!-- Forces all canvas elements to have aria-label to make page accessible -->
  <script>
    let canvasElements = document.getElementsByTagName('canvas');
    for (var i = 0; i < canvasElements.length; i++) {
      canvasElements[i].ariaLabel = "Waveform graphic";
    }
  </script>

  <!-- Function to scroll gridlines on recording editor -->
  <!-- Also scrolls the exampletrack -->
  <script>
    $(".recording-section").on("scroll", function() {
      // Get recording section
      let rec = $('.recording-section');

      // Scroll background position against scroll distance
      rec.css("background-position", -rec.get(0).scrollLeft);

      // Also, scroll along the new wav
      let new_wave = $("#recboxtempfilesslash<?php echo $user['id']; ?>");
      new_wave.css("margin-left", -rec.get(0).scrollLeft);
    });
  </script>
</body>

</html>