<?php

namespace App\Classes;

use InvalidArgumentException;

class Pacman
{
    /**
     * @var string  Permissible commands.
     */
    const COMMAND_PLACE = 'PLACE';
    const COMMAND_MOVE = 'MOVE';
    const COMMAND_LEFT = 'LEFT';
    const COMMAND_RIGHT = 'RIGHT';
    const COMMAND_REPORT = 'REPORT';

    /**
     * @var string  Permissible directions on board.
     */
    const DIRECTION_NORTH = 'NORTH';
    const DIRECTION_EAST = 'EAST';
    const DIRECTION_SOUTH = 'SOUTH';
    const DIRECTION_WEST = 'WEST';

    /**
     * @var string  Permissible rotations on board.
     */
    const ROTATION_LEFT = 'LEFT';
    const ROTATION_RIGHT = 'RIGHT';

    /**
     * @var \App\Classes\Board
     */
    protected $board;

    /**
     * @var integer  Horizontal position on board.
     */
    protected $x;

    /**
     * @var integer  Vertical position on board.
     */
    protected $y;

    /**
     * @var string  Direction facing on board.
     */
    protected $direction;

    /**
     * @var array  Directions map.
     */
    protected $directionMap = [
        self::DIRECTION_NORTH => self::DIRECTION_EAST,
        self::DIRECTION_EAST => self::DIRECTION_SOUTH,
        self::DIRECTION_SOUTH => self::DIRECTION_WEST,
        self::DIRECTION_WEST => self::DIRECTION_NORTH,
    ];


    /**
     * Create new pacman instance.
     *
     * @param  \App\Classes\Board $board
     * @return void
     */
    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    /**
     * Main function to execute the command.
     *
     * @param  string $command
     * @return void
     */
    public function execute($command)
    {
        // Parse command and extract arguments
        extract($this->parseCommand($command));

        // Execute pacman command with arguments
        switch ($option) {
            case self::COMMAND_PLACE:
                $this->place($x, $y, $direction);
                break;

            case self::COMMAND_MOVE:
                $this->move();
                break;

            case self::COMMAND_LEFT:
            case self::COMMAND_RIGHT:
                $this->rotate($option);
                break;

            case self::COMMAND_REPORT:
                echo $this->report();
                break;
        }
    }

    /**
     * Parse command, extracting method, x, y, and direction where applicable.
     *
     * @param  string $command
     * @return array
     */
    protected function parseCommand($option)
    {
        // Extract method and arguments from command
        preg_match(
            '/^' .
            '(?P<option>' . $this->getCommands('|') . ')' .
            '(\r\n|\s' .
            '(?P<x>\d+)\s?,' .
            '(?P<y>\d+)\s?,' .
            '(?P<direction>' . $this->getDirections('|') . ')' .
            ')?' .
            '$/',
            strtoupper($option),
            $results
        );

        // Extract captured arguments with fallback defaults
        $option = $results['option'] ?? null;
        $x = $results['x'] ?? 0;
        $y = $results['y'] ?? 0;
        $direction = $results['direction'] ?? self::DIRECTION_NORTH;

        return compact('option', 'x', 'y', 'direction');
    }

    /**
     * Place pacman on board.
     *
     * @param  integer $x
     * @param  integer $y
     * @param  string $direction
     * @return void
     */
    public function place($x, $y, $direction)
    {
        // Check if new x,y coordinates are valid with board bounds
        if (!$this->board->withinBounds($x, $y)) {
            throw new InvalidArgumentException(sprintf('Coordinates (%d,%d) outside board boundaries.', $x, $y));
        }

        // Check if supplied direction is valid or not
        if (!$this->isPermissibleDirection($direction)) {
            throw new InvalidArgumentException(sprintf('Direction (%s) is not recognised.', $direction));
        }

        // Set pacman position and direction
        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
    }

    /**
     * Move pacman forward one unit in current direction.
     *
     * @return void
     */
    public function move()
    {
        // Check that pacman is placed before executing command
        if (!$this->isPlaced()) {
            return;
        }

        // Get current pacman position
        $x = $this->x;
        $y = $this->y;

        // Determine new position based on current direction
        switch ($this->direction) {
            case self::DIRECTION_NORTH:
                $y += 1;
                break;

            case self::DIRECTION_EAST:
                $x += 1;
                break;

            case self::DIRECTION_SOUTH:
                $y -= 1;
                break;

            case self::DIRECTION_WEST:
                $x -= 1;
                break;
        }

        // Check if new x,y coordinates are valid with board bounds
        if (!$this->board->withinBounds($x, $y)) {
            return;
        }

        // Set pacman position
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * turn pacman left or right.
     *
     * @param  string $rotation
     * @return void
     */
    public function rotate($rotation)
    {
        // Check that pacman is placed before executing command
        if (!$this->isPlaced()) {
            return;
        }

        $this->direction = $this->rotatePacman($rotation);
    }

    /**
     * Report pacman position and direction - X,Y coordinates and direction facing.
     *
     * @return string
     */
    public function report()
    {
        // Check that pacman is placed before executing command
        if (!$this->isPlaced()) {
            return;
        }

        return $this->x . ',' . $this->y . ',' . $this->direction;
    }

    /**
     * Check whether pacman has been placed on board or not.
     *
     * @return boolean
     */
    public function isPlaced()
    {
        return (!is_null($this->x) && !is_null($this->y));
    }

    /**
     * Resolve pacman direction from given rotation.
     *
     * @param  string $rotation
     * @return string
     */
    protected function rotatePacman($rotation)
    {
        if (!$this->isPermissibleRotation($rotation)) {
            //throw new InvalidArgumentException(sprintf('Rotation (%s) is not recognised.', $rotation));
            throw new InvalidArgumentException($rotation . ' is not a valid command.');
        }

        // Determine direction of rotation - clockwise or anti-clockwise
        $clockwise = ($rotation === self::ROTATION_RIGHT);
        $mappings = $clockwise ? $this->directionMap : array_flip($this->directionMap);

        return $mappings[$this->direction];
    }

    /**
     * Get permissible commands
     *
     * @param  string|null $separator
     * @return string
     */
    protected function getCommands($separator = null)
    {
        $options = [
            self::COMMAND_PLACE,
            self::COMMAND_MOVE,
            self::COMMAND_LEFT,
            self::COMMAND_RIGHT,
            self::COMMAND_REPORT,
        ];

        return is_null($separator) ? $options : implode($separator, $options);
    }

    /**
     * Get permissible directions as array or string.
     *
     * @param  string|null $separator
     * @return array|string
     */
    protected function getDirections($separator = null)
    {
        $directions = [
            self::DIRECTION_NORTH,
            self::DIRECTION_EAST,
            self::DIRECTION_SOUTH,
            self::DIRECTION_WEST,
        ];

        return is_null($separator) ? $directions : implode($separator, $directions);
    }

    /**
     * Get permissible rotations as array or string.
     *
     * @param  string|null $separator
     * @return array|string
     */
    protected function getRotations($separator = null)
    {
        $rotations = [
            self::ROTATION_LEFT,
            self::ROTATION_RIGHT,
        ];

        return is_null($separator) ? $rotations : implode($separator, $rotations);
    }

    /**
     * Check whether given direction is a permissible direction.
     *
     * @param  string $direction
     * @return boolean
     */
    protected function isPermissibleDirection($direction)
    {
        return in_array($direction, $this->getDirections());
    }

    /**
     * Check whether given rotation is a permissible rotation.
     *
     * @param  string $rotation
     * @return boolean
     */
    protected function isPermissibleRotation($rotation)
    {
        return in_array($rotation, $this->getRotations());
    }
}
