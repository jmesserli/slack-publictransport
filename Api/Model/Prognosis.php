<?php

namespace PegNu\Api\Model;

class Prognosis extends Model
{
    /**
     * @var string
     */
    public $platform;

    /**
     * @var string
     */
    public $departure;

    /**
     * @var string
     */
    public $arrival;

    /**
     * @var int
     */
    public $capacity1st;

    /**
     * @var int
     */
    public $capacity2nd;

    /**
     * Prognosis constructor.
     *
     * @param string $platform
     * @param string $departure
     * @param string $arrival
     * @param int    $capacity1st
     * @param int    $capacity2nd
     */
    public function __construct($platform, $departure, $arrival, $capacity1st, $capacity2nd)
    {
        $this->platform = $platform;
        $this->departure = $departure;
        $this->arrival = $arrival;
        $this->capacity1st = $capacity1st;
        $this->capacity2nd = $capacity2nd;
    }

    public static function fromJson($prognosis)
    {
        return new self(
            self::tryGetField($prognosis, 'platform'),
            self::tryGetField($prognosis, 'departure'),
            self::tryGetField($prognosis, 'arrival'),
            self::tryGetField($prognosis, 'capacity1st'),
            self::tryGetField($prognosis, 'capacity2nd')
        );
    }
}
