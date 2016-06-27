<?php
namespace PegNu\Api\Model;

class Checkpoint
{
    /**
     * @var $station Location
     */
    public $station;

    /**
     * @var $arrival string|null
     */
    public $arrival;

    /**
     * @var $departure string|null
     */
    public $departure;

    /**
     * @var $platform string
     */
    public $platform;

    /**
     * @var $prognosis Prognosis
     */
    public $prognosis;

    /**
     * Checkpoint constructor.
     * @param Location $station
     * @param null|string $arrival
     * @param null|string $departure
     * @param string $platform
     * @param Prognosis $prognosis
     */
    public function __construct(Location $station, $arrival, $departure, $platform, Prognosis $prognosis)
    {
        $this->station = $station;
        $this->arrival = $arrival;
        $this->departure = $departure;
        $this->platform = $platform;
        $this->prognosis = $prognosis;
    }

    public static function fromJson($checkpoint)
    {
        return new Checkpoint(
            Location::fromJson($checkpoint["station"]),
            $checkpoint["arrival"],
            $checkpoint["departure"],
            $checkpoint["platform"],
            Prognosis::fromJson($checkpoint["prognosis"])
        );
    }
}