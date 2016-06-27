<?php
namespace PegNu\Api\Model;

class Prognosis
{
    /**
     * @var $platform string
     */
    public $platform;

    /**
     * @var $departure string
     */
    public $departure;

    /**
     * @var $arrival string
     */
    public $arrival;

    /**
     * @var $capacity1st int
     */
    public $capacity1st;

    /**
     * @var $capacity2nd int
     */
    public $capacity2nd;

    /**
     * Prognosis constructor.
     * @param string $platform
     * @param string $departure
     * @param string $arrival
     * @param int $capacity1st
     * @param int $capacity2nd
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
        return new Prognosis(
            $prognosis["platform"],
            $prognosis["departure"],
            $prognosis["arrival"],
            $prognosis["capacity1st"],
            $prognosis["capacity2nd"]
        );
    }
}