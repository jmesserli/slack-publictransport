<?php

namespace PegNu\Api\Model;

class Section extends Model
{
    /**
     * @var Journey
     */
    public $journey;

    /**
     * @var string|null
     */
    public $walk;

    /**
     * @var Checkpoint
     */
    public $departure;

    /**
     * @var Checkpoint
     */
    public $arrival;

    /**
     * Section constructor.
     *
     * @param Journey     $journey
     * @param null|string $walk
     * @param Checkpoint  $departure
     * @param Checkpoint  $arrival
     */
    public function __construct(Journey $journey, $walk, Checkpoint $departure, Checkpoint $arrival)
    {
        $this->journey = $journey;
        $this->walk = $walk;
        $this->departure = $departure;
        $this->arrival = $arrival;
    }

    public static function fromJson($section)
    {
        return new self(
            Journey::fromJson(self::tryGetField($section, 'journey', [])),
            self::tryGetField(self::tryGetField($section, 'walk', []), 'duration'),
            Checkpoint::fromJson(self::tryGetField($section, 'departure')),
            Checkpoint::fromJson(self::tryGetField($section, 'arrival'))
        );
    }
}
