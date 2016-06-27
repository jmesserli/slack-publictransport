<?php
namespace PegNu\Api\Model;

class Location
{
    /**
     * @var $id string
     */
    public $id;

    /**
     * @var $type string
     */
    public $type;

    /**
     * @var $name string
     */
    public $name;

    /**
     * @var $score int
     */
    public $score;

    /**
     * @var $coordinates Coordinates
     */
    public $coordinates;

    /**
     * @var $distance string
     */
    public $distance;

    /**
     * Location constructor.
     * @param string $id
     * @param string $type
     * @param string $name
     * @param int $score
     * @param Coordinates $coordinates
     * @param string $distance
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
        return new Location(
            $location["id"],
            $location["type"],
            $location["name"],
            $location["score"],
            Coordinates::fromJson($location["coordinates"]),
            $location["distance"]
        );
    }
}