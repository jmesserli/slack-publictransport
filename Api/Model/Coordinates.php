<?php

namespace PegNu\Api\Model;

class Coordinates
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $x;

    /**
     * @var string
     */
    public $y;

    /**
     * Coordinates constructor.
     *
     * @param string $type
     * @param string $x
     * @param string $y
     */
    public function __construct($type, $x, $y)
    {
        $this->type = $type;
        $this->x = $x;
        $this->y = $y;
    }

    public static function fromJson($coordinates)
    {
        return new self(
            $coordinates['type'],
            $coordinates['x'],
            $coordinates['y']
        );
    }
}
