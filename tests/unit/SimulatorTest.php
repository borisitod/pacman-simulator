<?php

namespace Test;

use App\Classes\Board;
use App\Classes\Pacman;
use App\Classes\Simulator;
use PHPUnit\Framework\TestCase;

class SimulatorTest extends TestCase
{
    /**
     * @var Board instance.
     */
    protected $board;

    /**
     * @var Pacman instance.
     */
    protected $pacman;

    /**
     * \App\Classes\Simulator  Simulator instance.
     */
    protected $simulator;



    /**
     * Set up instance.
     *
     * @return void
     */
    public function setUp()
    {
        $this->board = new Board(5, 5);
        $this->pacman = new Pacman($this->board);
        $this->simulator = new Simulator($this->pacman);
    }

    /**
     * Test running simulator.
     *
     * @covers \App\Classes\Simulator::run
     * @throws \Exception
     * @return void
     */
    public function testRun()
    {
        $this->expectOutputString('0,1,NORTH');
        $this->simulator->run(__DIR__ .'/test.txt');
    }
}
