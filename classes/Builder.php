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
    public static function playableWaveform($location, $audioName, $id, $delete = true)
    {
        $clean_location = strtok(str_replace("/", "slash", str_replace("-", "dash", $location)), ".");

        echo "
        <!-- Audio tag to store the associated audio location -->
        <audio id='$location'>
            <source src='$location' type='audio/wav'>
        </audio>


        <div class='recording-box' style='width: 600px;'>
        <div class='recording-box-panel'>
          <!-- Toggle the audio when clicking on the play button -->
          <a onclick='togglePlay($clean_location,\"img$clean_location\")'>
            <!-- Image swapped to pause on togglePlay -->
            <img src='images/PlaySymbol.png' class='circular-button' alt='Play' id='img$clean_location'>
          </a>
        </div>
        <div class='recording-waveform-end'>
          <div class='recording-box-label'>
            <p>$audioName</p>
          </div>
          <div class='recording-waveform'>
            <!-- Location for waveform -->
            <div style='margin-top:-32px;' id='$clean_location'></div>
            
            <!-- script to place waveform in previous div (needs to be placed immediately after)-->
            <script>
                var $clean_location = WaveSurfer.create({
                    container: '#$clean_location',
                    waveColor: 'black',
                    progressColor: 'purple'
                });

                // Load the correct audio
                $clean_location.load('$location');
            </script>
          </div>
        </div>
        ";
        // Add delete icon if delete is set to true
        if ($delete === true) {
            echo "
        <a href='?command=delete&id=$id'>
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

            <!-- This form is used to store the audio data and submit on upload click -->
            <form method="post" name="uploadform" style="margin:0px; padding:0px; display:inline;">
                <!-- Hidden until recording finished -->
                <div name="uploadhider" id="uploadhider" style="display:none;" class="recording-box-panel">
                    <!-- Stores audio data -->
                    <input type="hidden" name="record" id="record" />
                    <!-- Submits form -->
                    <button style="border:none;background:none;margin:0px; padding:0px; display:inline;" type="submit">
                        <img src="images/upload.png" class="circular-button" alt="Record">
                    </button>
                </div>

                <div class="recording-waveform-end">
                    <div class="new-recording-waveform">
                        <input type="text" name="name" class="form-control" id="name" placeholder="Recording Name" required />
                    </div>
                </div>
            </form>
        </div>
        ';
    }
}
