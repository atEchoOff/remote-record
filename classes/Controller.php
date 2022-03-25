<?php

class Controller
{
    private $command;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public function run()
    {
        switch ($this->command) {
            case "home":
                $this->home();
                break;
            case "composition":
                $this->composition();
                break;
            case "new_composition":
                $this->new_composition();
                break;
            case "record":
                $this->record();
                break;
            default:
                $this->home();
        }
    }

    private function home()
    {
        include "templates/home.php";
    }

    private function composition()
    {
        include "templates/composition.php";
    }

    private function new_composition()
    {
        include "templates/new_composition.php";
    }

    private function record()
    {
        include "templates/record.php";
    }
}
