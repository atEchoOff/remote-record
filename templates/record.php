<!-- Home Page -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Jimmy Connors and Brian Christner">
  <title>Record Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="styles/main.css">
  <!-- This needs to be placed at the top to function correctly -->
  <script src="https://unpkg.com/wavesurfer.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
  <!-- Navbar -->
  <?php Builder::navbar(); ?>

  <!-- Your recordings -->
  <div class="box-section">
    <div style="overflow:auto;">
      <h2 class="box-title">Recordings</h2>
      <div class="btn-toolbar box-buttons" role="toolbar" aria-label="Toolbar with button groups">
        <!-- Only add composer button if the user is the composer of this page -->
        <?php
        if ($_SESSION["email"] === $composition["composer_email"]) {
          echo '
          <div class="btn-group mr-2" role="group" aria-label="First group">
            <a class="btn btn-dark" href="?command=composition&composition=' . $composition["name"] . '">Switch to Composer Mode</a>
          </div>
          ';
        }
        ?>
      </div>
    </div>
    <div class="recording-section">
      <!-- Display all recordings -->
      <?php
      if (sizeof($recordings) === 0) {
        echo "<h5>No recordings</h5>";
      }
      // For each recording, show a waveform
      foreach ($recordings as $recording) {
        Builder::playableWaveform($recording["location"], $recording["name"], $recording["id"], $delete = true);
      }
      ?>
    </div>

    <div style="clear:both"></div>
    <!-- Create New Recordings Section -->
    <h2 class="box-title">Create New Recording</h2>
    <div style="clear:both"></div>
    <div class="recording-section">
      <!-- Backing Track -->
      <?php Builder::playableWaveform($composition["location"], "Backing Track", null, $delete = false) ?>
      <!-- New Recording Box -->
      <?php Builder::recordableWaveform(); ?>
    </div>
    <div style="clear:both"></div>
  </div>

  <!-- Set up microphone access -->
  <script>
    // Whether or not we are currently recording
    var recording = false;

    // Stores audio data
    var recordedChunks = [];

    // Set up audio recorder (first time, wait for permissions to be given)
    var options = {
      mimeType: 'audio/webm'
    };
    var mediaRecorder;

    // When permissions are given, initialize the media recorder for the given mic
    const handleSuccess = function(stream) {
      mediaRecorder = new MediaRecorder(stream, options);
      mediaRecorder.ondataavailable = handleDataAvailable;
    }

    // Query only audio devices (no video)
    navigator.mediaDevices.getUserMedia({
        audio: true,
        video: false
      })
      .then(handleSuccess);

    // When recording data is available, save and upload data
    // Recording is stored with no parameter, so only one blob is used to store mic data
    // So, this should only be called once, containing all audio data
    function handleDataAvailable(event) {
      if (event.data.size > 0) {
        recordedChunks.push(event.data);
        upload();
      } else {
        // ...
      }
    }
  </script>

  <!-- Script to record (usable only once) -->
  <script>
    function record() {
      if (!recording) {
        recording = true;

        // Get backtrack waveform
        let backtrack = audioslash<?php echo str_replace(" ", "space", $composition["name"]); ?>;
        // Stop and go to beginning
        backtrack.stop();

        // Record and play backtrack simultaneously
        mediaRecorder.start();
        backtrack.play();

        // Replace record icon with 3 dots
        document.getElementById("recordicon").src = "images/dotdotdot.png";

        // When audio is complete...
        setTimeout(function() {
          recording = false;
          // stop recording
          // stopping the recording calls the handleDataAvailable function
          // this will store the audio data into a hidden input in a form
          mediaRecorder.stop();
          // show upload button for this form
          document.getElementById("uploadhider").style = "display:inline;";
        }, document.getElementById("<?php echo $composition["location"]; ?>").duration * 1000);
      }
    }

    // Sets form hidden value to contain audio data for post
    function upload() {
      // Save audio as wav file (temporary, just to read the bytes)
      let file = new File([new Blob(recordedChunks)], "temp_hidden.wav", {
        type: 'audio/wav'
      });

      // Read bytes from file
      // https://stackoverflow.com/questions/37134433/convert-input-file-to-byte-array
      var reader = new FileReader();
      var fileByteArray = [];
      reader.readAsArrayBuffer(file);
      reader.onloadend = function(evt) {
        if (evt.target.readyState == FileReader.DONE) {
          var arrayBuffer = evt.target.result,
            array = new Uint8Array(arrayBuffer);
          for (var i = 0; i < array.length; i++) {
            fileByteArray.push(array[i]);
          }
        }

        // Save file bytes into record input for form
        document.getElementById("record").value = fileByteArray.toString();
      }
    }
  </script>

  <!-- Script to play audio, plays playableWaveform with given waveform object and image ID -->
  <!-- Temporarily, do not toggle images to fix a glitch on audio finishing but not pausing -->
  <script>
    function togglePlay(waveplayer, imgID) {
      // if audio is already being recorded, exit
      if (recording) {
        return;
      }
      // if (waveplayer.isPlaying()) {
      //   document.getElementById(imgID).src = "images/PlaySymbol.png";
      // } else {
      //   document.getElementById(imgID).src = "images/PauseSymbol.webp";
      // }
      // Toggle the player
      waveplayer.playPause();
    }
  </script>

  <!-- Forces all canvas elements to have aria-label to make page accessible -->
  <script>
    let canvasElements = document.getElementsByTagName('canvas');
    console.log(canvasElements.length);
    for (var i = 0; i < canvasElements.length; i++) {
      canvasElements[i].ariaLabel = "Waveform graphic";
    }
  </script>

  <!-- Disable enter to submit form -->
  <!-- https://stackoverflow.com/questions/895171/prevent-users-from-submitting-a-form-by-hitting-enter -->
  <script>
    $(document).ready(function() {
      $(window).keydown(function(event) {
        if (event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>