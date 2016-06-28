<?php

namespace PegNu\Api\Model;

class Checkpoint extends Model
{
    /**
     * @var Location
     */
    public $station;

    /**
     * @var string|null
     */
    public $arrival;

    /**
     * @var string|null
     */
    public $departure;

    /**
     * @var string
     */
    public $platform;

    /**
     * @var Prognosis
     */
    public $prognosis;

    /**
     * Checkpoint constructor.
     *
     * @param Location    $station
     * @param null|string $arrival
     * @param null|string $departure
     * @param string      $platform
     * @param Prognosis   $prognosis
     */
    public function __construct(Location $station, $arrival, $departure, $platform, Prognosis $prognosis)
    {
        $this->station   = $station;
        $this->arrival   = $arrival;
        $this->departure = $departure;
        $this->platform  = $platform;
        $this->prognosis = $prognosis;
    }

    public static function fromJson($checkpoint)
    {
        return new self(
            Location::fromJson(self::tryGetField($checkpoint, "station", [])),
            self::tryGetField($checkpoint, "arrival"),
            self::tryGetField($checkpoint, "departure"),
            self::tryGetField($checkpoint, "platform"),
            Prognosis::fromJson(self::tryGetField($checkpoint, 'prognosis'))
        );
    }
}
