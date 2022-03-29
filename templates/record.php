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
</head>

<body>
  <!-- Navbar -->
  <?php Builder::navbar(); ?>

  <!-- Your recordings -->
  <div class="box-section">
    <div style="overflow:auto;">
      <h2 class="box-title">Recordings</h2>
      <div class="btn-toolbar box-buttons" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group mr-2" role="group" aria-label="First group">
          <a class="btn btn-dark" href="?command=composition">Switch to Composer Mode</a>
        </div>
      </div>
    </div>
    <div class="recording-section">
      <!-- Display all recordings -->
      <?php
      if (sizeof($recordings) === 0) {
        echo "<h5>No recordings</h5>";
      }
      foreach ($recordings as $recording) {
        Builder::playableWaveform($recording["location"], $recording["name"], $delete = true);
      }
      ?>
    </div>

    <div style="clear:both"></div>
    <!-- Create New Recordings Section -->
    <h2 class="box-title">Create New Recording</h2>
    <div style="clear:both"></div>
    <div class="recording-section">
      <!-- Backing Track -->
      <?php Builder::playableWaveform($composition["location"], "Backing Track", $delete = false) ?>
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

    const handleSuccess = function(stream) {
      mediaRecorder = new MediaRecorder(stream, options);
      mediaRecorder.ondataavailable = handleDataAvailable;
    }

    navigator.mediaDevices.getUserMedia({
        audio: true,
        video: false
      })
      .then(handleSuccess);

    // When data is available, save and upload the data
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

        // Get audio
        let x = document.getElementById("<?php echo $composition["location"]; ?>");

        // Record and play backtrack simultaneously
        mediaRecorder.start();
        x.play();

        // Replace record icon with 3 dots
        document.getElementById("recordicon").src = "images/dotdotdot.png";

        // When audio is complete...
        setTimeout(function() {
          // stop recording
          mediaRecorder.stop();
          // show upload icon
          document.getElementById("uploadhider").style = "display:inline;";
        }, x.duration * 1000);
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>