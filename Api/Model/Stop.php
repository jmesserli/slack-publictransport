<?php
namespace PegNu\Api\Model;

class Stop
{
    /**
     * @var $station Location
     */
    public $station;

    /**
     * @var $name string
     */
    public $name;

    /**
     * @var $category string
     */
    public $category;

    /**
     * @var $number int
     */
    public $number;

    /**
     * @var $operator string
     */
    public $operator;

    /**
     * @var $to string
     */
    public $to;

    /**
     * Stop constructor.
     * @param Location $station
     * @param string $name
     * @param string $category
     * @param int $number
     * @param string $operator
     * @param string $to
     */
    public function __construct(Location $station, $name, $category, $number, $operator, $to)
    {
        $this->station = $station;
        $this->name = $name;
        $this->category = $category;
        $this->number = $number;
        $this->operator = $operator;
        $this->to = $to;
    }

    public static function fromJson($stop)
    {
        return new Stop(
            Location::fromJson($stop["station"]),
            $stop["name"],
            $stop["category"],
            $stop["number"],
            $stop["operator"],
            $stop["to"]
        );
    }

}