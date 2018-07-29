<?php

use App\Classes\Board;
use App\Classes\Pacman;
use PHPUnit\Framework\TestCase;

class PacmanTest extends TestCase
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
     * Set up board and pacman.
     *
     * @return void
     */
    public function setUp()
    {
        $this->board = new Board(5, 5);
        $this->pacman = new Pacman($this->board);
    }


    /**
     *
     * Test valid commands are parsed and executed.
     *@test
     * @covers \App\Classes\Pacman::execute
     * @throws \Exception
     * @return void
     */
    public function testValidCommandsAreExecuted()
    {
        $this->pacman->execute('PLACE 1,1,NORTH');
        $this->pacman->execute('MOVE');
        $this->pacman->execute('LEFT');
        $this->pacman->execute('RIGHT');
        $this->assertEquals('1,2,NORTH', $this->pacman->report());
    }

    /**
     * Test invalid place commands throw exception.
     *
     * @covers \App\Classes\Pacman::place
     * @return void
     */
    public function testInvalidPlaceCommandsThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->pacman->place(5, 5, Pacman::DIRECTION_NORTH);
        $this->pacman->place(0, 0, 'OK');
    }

    /**
     * Test valid move commands are executed.
     *
     * @covers \App\Classes\Pacman::move
     * @throws \Exception
     * @return void
     */
    public function testValidMoveCommandsAreExecuted()
    {
        $this->pacman->place(2, 2, Pacman::DIRECTION_NORTH);
        $this->pacman->move();
        $this->assertEquals($this->pacman->report(), '2,3,' . Pacman::DIRECTION_NORTH);

        $this->pacman->place(2, 2, Pacman::DIRECTION_EAST);
        $this->pacman->move();
        $this->assertEquals($this->pacman->report(), '3,2,' . Pacman::DIRECTION_EAST);

        $this->pacman->place(2, 2, Pacman::DIRECTION_SOUTH);
        $this->pacman->move();
        $this->assertEquals($this->pacman->report(), '2,1,' . Pacman::DIRECTION_SOUTH);

        $this->pacman->place(2, 2, Pacman::DIRECTION_WEST);
        $this->pacman->move();
        $this->assertEquals($this->pacman->report(), '1,2,' . Pacman::DIRECTION_WEST);
    }

    /**
     * Test invalid move commands are ignored.
     *
     * @covers \App\Classes\Pacman::move
     * @throws \Exception
     * @return void
     */
    public function testInvalidMoveCommandsAreIgnored()
    {
        $this->pacman->place(2, 4, Pacman::DIRECTION_NORTH);
        $this->pacman->move();
        $this->assertEquals($this->pacman->report(), '2,4,' . Pacman::DIRECTION_NORTH);

        $this->pacman->place(4, 2, Pacman::DIRECTION_EAST);
        $this->pacman->move();
        $this->assertEquals($this->pacman->report(), '4,2,' . Pacman::DIRECTION_EAST);

        $this->pacman->place(2, 0, Pacman::DIRECTION_SOUTH);
        $this->pacman->move();
        $this->assertEquals($this->pacman->report(), '2,0,' . Pacman::DIRECTION_SOUTH);

        $this->pacman->place(0, 2, Pacman::DIRECTION_WEST);
        $this->pacman->move();
        $this->assertEquals($this->pacman->report(), '0,2,' . Pacman::DIRECTION_WEST);
    }

    /**
     * Test valid rotate commands are executed.
     *
     * @covers \App\Classes\Pacman::rotate
     * @throws \Exception
     * @return void
     */
    public function testValidRotateCommandsAreExecuted()
    {
        $this->pacman->place(2, 2, Pacman::DIRECTION_NORTH);
        $this->pacman->rotate(Pacman::ROTATION_LEFT);
        $this->assertEquals($this->pacman->report(), '2,2,' . Pacman::DIRECTION_WEST);

        $this->pacman->place(2, 2, Pacman::DIRECTION_NORTH);
        $this->pacman->rotate(Pacman::ROTATION_RIGHT);
        $this->assertEquals($this->pacman->report(), '2,2,' . Pacman::DIRECTION_EAST);

        $this->pacman->place(2, 2, Pacman::DIRECTION_EAST);
        $this->pacman->rotate(Pacman::ROTATION_LEFT);
        $this->assertEquals($this->pacman->report(), '2,2,' . Pacman::DIRECTION_NORTH);

        $this->pacman->place(2, 2, Pacman::DIRECTION_EAST);
        $this->pacman->rotate(Pacman::ROTATION_RIGHT);
        $this->assertEquals($this->pacman->report(), '2,2,' . Pacman::DIRECTION_SOUTH);

        $this->pacman->place(2, 2, Pacman::DIRECTION_SOUTH);
        $this->pacman->rotate(Pacman::ROTATION_LEFT);
        $this->assertEquals($this->pacman->report(), '2,2,' . Pacman::DIRECTION_EAST);

        $this->pacman->place(2, 2, Pacman::DIRECTION_SOUTH);
        $this->pacman->rotate(Pacman::ROTATION_RIGHT);
        $this->assertEquals($this->pacman->report(), '2,2,' . Pacman::DIRECTION_WEST);

        $this->pacman->place(2, 2, Pacman::DIRECTION_WEST);
        $this->pacman->rotate(Pacman::ROTATION_LEFT);
        $this->assertEquals($this->pacman->report(), '2,2,' . Pacman::DIRECTION_SOUTH);

        $this->pacman->place(2, 2, Pacman::DIRECTION_WEST);
        $this->pacman->rotate(Pacman::ROTATION_RIGHT);
        $this->assertEquals($this->pacman->report(), '2,2,' . Pacman::DIRECTION_NORTH);
    }

    /**
     * Test invalid rotate commands throw exception.
     *
     * @covers \App\Classes\Pacman::rotate
     * @return void
     */
    public function testInvalidRotateCommandsThrowException()
    {
        $this->expectExceptionMessage('DOWN is not a valid command');
        $this->pacman->place(2, 2, Pacman::DIRECTION_NORTH);
        $this->pacman->rotate('DOWN');
    }

    /**
     * Test report commands are executed.
     *
     * @covers \App\Classes\Pacman::report
     * @throws \Exception
     * @return void
     */
    public function testReportCommandsAreExecuted()
    {
        $this->pacman->place(0, 0, Pacman::DIRECTION_NORTH);
        $this->assertEquals($this->pacman->report(), '0,0,' . Pacman::DIRECTION_NORTH);

        $this->pacman->place(0, 4, Pacman::DIRECTION_EAST);
        $this->assertEquals($this->pacman->report(), '0,4,' . Pacman::DIRECTION_EAST);

        $this->pacman->place(4, 4, Pacman::DIRECTION_SOUTH);
        $this->assertEquals($this->pacman->report(), '4,4,' . Pacman::DIRECTION_SOUTH);

        $this->pacman->place(4, 0, Pacman::DIRECTION_WEST);
        $this->assertEquals($this->pacman->report(), '4,0,' . Pacman::DIRECTION_WEST);
    }
}