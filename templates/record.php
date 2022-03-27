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
      <!-- Recording Box Template -->
      <?php Builder::playableWaveform("fake loc", "Recording 2", $delete = true) ?>
      <!-- End of template -->
      <?php Builder::playableWaveform("fake loc", "Recording 3", $delete = true) ?>
    </div>

    <div style="clear:both"></div>
    <!-- Create New Recordings Section -->
    <h2 class="box-title">Create New Recording</h2>
    <div style="clear:both"></div>
    <div class="recording-section">
      <!-- Backing Track -->
      <?php Builder::playableWaveform($composition["location"], "Backing Track", $delete = false) ?>
      <!-- New Recording Box -->
      <div class="recording-box">
        <div class="recording-box-panel">
          <a href="">
            <img src="images/recording.png" class="circular-button" alt="Record">
          </a>
        </div>
        <div class="recording-waveform-end">
          <div class="recording-box-label">
            <p>New Recording</p>
          </div>
          <div class="new-recording-waveform">

          </div>
        </div>
      </div>
    </div>
    <div style="clear:both"></div>
  </div>

  <script>
    function togglePlay(name) {
      var x = document.getElementById(name);

      if (x.paused) {
        console.log("Play");
        x.play();
      } else {
        console.log("Pause");
        x.pause();
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>