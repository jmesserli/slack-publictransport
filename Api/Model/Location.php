<?php

namespace PegNu\Api\Model;

class Location extends Model
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $score;

    /**
     * @var Coordinates
     */
    public $coordinates;

    /**
     * @var string
     */
    public $distance;

    /**
     * Location constructor.
     *
     * @param string      $id
     * @param string      $type
     * @param string      $name
     * @param int         $score
     * @param Coordinates $coordinates
     * @param string      $distance
     */
    public function __construct($id, $type, $name, $score, Coordinates $coordinates, $distance)
    {
        $this->id          = $id;
        $this->type        = $type;
        $this->name        = $name;
        $this->score       = $score;
        $this->coordinates = $coordinates;
        $this->distance    = $distance;
    }

    public static function fromJson($location)
    {
        return new self(
            self::tryGetField($location, "id"),
            self::tryGetField($location, "type"),
            self::tryGetField($location, "name"),
            self::tryGetField($location, "score"),
            Coordinates::fromJson(self::tryGetField($location, "coordinates", [])),
            self::tryGetField($location, "distance")
        );
    }
}
