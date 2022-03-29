<?php

/**
 * A class with HTML/PHP excerpts to help build HTML pages cleanly
 */

class Builder
{
    /**
     * Navbar display
     */
    public static function navbar()
    {
        echo '
        <nav class="navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Remote Record</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="?command=home">Home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="?command=logout">Log Out</a>
                    </li>
                </ul>
            </div>
        </nav>
        ';
    }
    /**
     * Creates a playable waveform display, if delete is true, add delete icon
     */
    public static function playableWaveform($location, $audioName, $delete = true)
    {
        echo "
        <!-- Audio tag to store the associated audio location -->
        <audio id='$location'>
            <source src='$location' type='audio/wav'>
        </audio>


        <div class='recording-box' style='width: 600px'>
        <div class='recording-box-panel'>
          <!-- Play the audio when clicking on the play button -->
          <a onclick='togglePlay(\"$location\")'>
            <img src='images/PlaySymbol.png' class='circular-button' alt='Play'>
          </a>
        </div>
        <div class='recording-waveform-end'>
          <div class='recording-box-label'>
            <p>$audioName</p>
          </div>
          <div class='recording-waveform'>
            <img src='WaveForm.webp' alt='Waveform'>
          </div>
        </div>
        ";
        // Add delete icon if delete is set to true
        if ($delete === true) {
            echo "
        <a href=''>
          <img src='images/delete.png' class='delete-button' alt='Delete'>
        </a>
        ";
        }
        echo "
          </div>
        <div style='clear:both'>
      </div>
    ";
    }

    /**
     * 
     * Creates a recording waveform display
     */
    public static function recordableWaveform()
    {
        echo '
        <div class="recording-box" style="width:600px;">
            <div class="recording-box-panel">
                <!-- Button to toggle whether or not user is being recorded -->
                <a onclick="record()">
                    <img src="images/recording.png" class="circular-button" alt="Record" id="recordicon" name="recordicon">
                </a>
            </div>

            <div name="uploadhider" id="uploadhider" style="display:none;" class="recording-box-panel">
                <!-- This form is used to store the audio data and submit on upload click -->
                <form method="post" name="uploadform" style="margin:0px; padding:0px; display:inline;">
                    <!-- Stores audio data -->
                    <input type="hidden" name="record" id="record" />
                    <!-- Submits form -->
                    <button style="border:none;background:none;margin:0px; padding:0px; display:inline;" type="submit">
                        <img src="images/upload.png" class="circular-button" alt="Record">
                    </button>
                </form>
            </div>

            <div class="recording-waveform-end">
                <div class="recording-box-label">
                    <p>New Recording</p>
                </div>
                <div class="new-recording-waveform">

                </div>
            </div>
        </div>
        ';
    }
}
