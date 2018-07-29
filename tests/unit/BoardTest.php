<?php

namespace Test;

use App\Classes\Board;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    /**
     * \App\Classes\Board  Board instance.
     */
    protected $board;


    /**
     * Set up test case.
     *
     * @return void
     */
    public function setUp()
    {
        $this->board = new Board(5, 5);
    }

    /**
     * Test coordinates that are within board bounds.
     *
     * @covers \App\Classes\Board::withinBounds
     * @throws \Exception
     */
    public function testCoordinatesWithinBounds()
    {
        $this->assertTrue($this->board->withinBounds(0, 0));
        $this->assertTrue($this->board->withinBounds(3, 4));
        $this->assertTrue($this->board->withinBounds(0, 4));
        $this->assertTrue($this->board->withinBounds(4, 0));
        $this->assertTrue($this->board->withinBounds(4, 4));
    }

    /**
     * Test coordinates that are outside board bounds.
     *
     * @covers \App\Classes\Board::withinBounds
     * @throws \Exception
     * @return void
     */
    public function testCoordinatesOutsideBounds()
    {
        $this->assertFalse($this->board->withinBounds(0, -1));
        $this->assertFalse($this->board->withinBounds(-1, -1));
        $this->assertFalse($this->board->withinBounds(5, 0));
        $this->assertFalse($this->board->withinBounds(0, 5));
        $this->assertFalse($this->board->withinBounds(5, 5));
    }
}
