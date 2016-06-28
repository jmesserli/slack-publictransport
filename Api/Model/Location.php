<?php

namespace PegNu\Api\Model;

class Location
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
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->score = $score;
        $this->coordinates = $coordinates;
        $this->distance = $distance;
    }

    public static function fromJson($location)
    {
        return new self(
            $location['id'],
            $location['type'],
            $location['name'],
            $location['score'],
            Coordinates::fromJson($location['coordinates']),
            $location['distance']
        );
    }
}
