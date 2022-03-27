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
     * Creates a playable waveform display
     */
    public static function playableWaveform($location, $audioName, $delete = true)
    {
        echo "
        <audio id='$location'>
            <source src='$location' type='audio/wav'>
        </audio>
        <div class='recording-box' style='width: 600px'>
        <div class='recording-box-panel'>
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
        if ($delete === true) {
            echo "
        <a href=''>
          <img src='images/delete.png' class='delete-button' alt='Delete'>
        </a>
        ";
        }
        echo "
      </div>
      <div style='clear:both'></div>
    ";
    }
}
