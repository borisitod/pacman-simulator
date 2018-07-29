<?php

namespace App\Classes;

class Simulator
{
    /**
     * @var \App\Classes\Pacman  Pacman instance.
     */
    protected $pacman;


    /**
     * Create new Simulator instance.
     *
     * @param  \App\Classes\Pacman  $pacman
     * @return void
     */
    public function __construct(Pacman $pacman)
    {
        $this->pacman = $pacman;
    }

    /**
     * Run simulator, reading instructions from given input source.
     *
     * @param  string   $source
     * @return void
     */
    public function run($source)
    {
        $handle = fopen($source, 'r');


        while (($command = fgets($handle))) {
            $this->pacman->execute($command);
        }

        fclose($handle);
    }
}
