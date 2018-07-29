<?php

require 'vendor/autoload.php';

use App\Classes\Board;
use App\Classes\Pacman;
use App\Classes\Simulator;

// Create pacman, board and simulator
$pacman = new Pacman(new Board(5, 5));
$simulator = new Simulator($pacman);

// Parse command line arguments and run
$source = $argv[1] ?? 'php://stdin';

$simulator->run($source);
