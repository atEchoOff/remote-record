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
            <a class="navbar-brand" style="margin-left: 10px;" href="#"><strong>Composer</strong></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="?command=home">Your Projects</a>
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
     * If drag is true, make the element draggable
     */
    public static function playableWaveform($location, $audioName, $id, $delete = true, $drag = false, $width = 1024, $product = false)
    {
        $clean_location = Utils::cleanLocation($location);

        echo "
<!-- Audio tag to store the associated audio location -->
<audio id='$location' preload='metadata'>
    <source src='$location' type='audio/wav'>
</audio>

<!-- Draggable element with adjustable left margin and width for zooming -->
<div id='recbox$clean_location' style='margin-left:0px;width: " . $width . "px;'>
    <div class='recording-box' style='width:100%;'>
        <div class='recording-box-panel'>
            <!-- Toggle the audio when clicking on the play button -->
            <button class='hidden-button' onclick='togglePlay($clean_location,\"img$clean_location\")'>

                <!-- Image swapped to pause on togglePlay -->
                <img src='images/PlaySymbol.png' class='circular-button' alt='Play' id='img$clean_location'>
            </button>
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
                        waveColor: '#000566',
                        progressColor: '#000342'
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
        <a href='?command=delete&id=$id&product=$product'>
            <img src='images/delete.svg' class='delete-button' alt='Delete'>
        </a>
        ";
        }
        echo "
    </div>
    <div style='clear:both'></div>
</div>
    ";

        // Make an element draggable if it is requested
        if ($drag === true) {
            echo "
<script>
    dragElement(document.getElementById('recbox$clean_location'));
    addKeyboardDrag(document.getElementById('recbox$clean_location'));
</script>
                ";
        }
    }

    /**
     * Creates a recording waveform display
     */
    public static function recordableWaveform()
    {
        echo '
<div class="recording-box" style="width:1024px;">
    <div class="recording-box-panel">
        <!-- Button to start recording user -->
        <button onclick="record()" class="hidden-button">
            <img src="images/recording.png" class="circular-button" alt="Record" id="recordicon">
        </button>
    </div>

    <!-- This form is used to store the audio data and submit on upload click -->
    <form method="post" name="uploadform" style="margin:0px; padding:0px; display:inline;">
        <!-- Hidden until recording finished -->
        <div id="uploadhider" style="display:none;" class="recording-box-panel">

            <!-- Stores audio data -->
            <input type="hidden" name="record" id="record" value="" />

            <!-- Submits form, button appears after recording finished -->
            <button class="hidden-button" type="submit">
                <img src="images/upload.svg" class="circular-button" alt="Record">
            </button>
        </div>

        <!-- Location of the name textbox for the recording -->
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
