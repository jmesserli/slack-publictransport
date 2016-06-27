<?php
namespace PegNu\Api\Model;

class Coordinates
{

    /**
     * @var string $type
     */
    public $type;

    /**
     * @var string $x
     */
    public $x;

    /**
     * @var string $y
     */
    public $y;

    /**
     * Coordinates constructor.
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
        return new Coordinates(
            $coordinates["type"],
            $coordinates["x"],
            $coordinates["y"]
        );
    }
}