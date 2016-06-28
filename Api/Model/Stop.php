<?php

namespace PegNu\Api\Model;

class Stop extends Model
{
    /**
     * @var Location
     */
    public $station;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $category;

    /**
     * @var int
     */
    public $number;

    /**
     * @var string
     */
    public $operator;

    /**
     * @var string
     */
    public $to;

    /**
     * Stop constructor.
     *
     * @param Location $station
     * @param string   $name
     * @param string   $category
     * @param int      $number
     * @param string   $operator
     * @param string   $to
     */
    public function __construct(Location $station, $name, $category, $number, $operator, $to)
    {
        $this->station  = $station;
        $this->name     = $name;
        $this->category = $category;
        $this->number   = $number;
        $this->operator = $operator;
        $this->to       = $to;
    }

    public static function fromJson($stop)
    {
        return new self(
            Location::fromJson(self::tryGetField($stop, "station", [])),
            self::tryGetField($stop, "name"),
            self::tryGetField($stop, "category"),
            self::tryGetField($stop, "number"),
            self::tryGetField($stop, "operator"),
            self::tryGetField($stop, "to")
        );
    }
}
