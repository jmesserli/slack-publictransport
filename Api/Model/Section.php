<?php
namespace PegNu\Api\Model;

class Section
{
    /**
     * @var $journey Journey
     */
    public $journey;

    /**
     * @var $walk string|null
     */
    public $walk;

    /**
     * @var  $departure Checkpoint
     */
    public $departure;

    /**
     * @var $arrival Checkpoint
     */
    public $arrival;

    /**
     * Section constructor.
     * @param Journey $journey
     * @param null|string $walk
     * @param Checkpoint $departure
     * @param Checkpoint $arrival
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
        return new Section(
            Journey::fromJson($section["journey"]),
            $section["walk"],
            Checkpoint::fromJson($section["departure"]),
            Checkpoint::fromJson($section["arrival"])
        );
    }
}