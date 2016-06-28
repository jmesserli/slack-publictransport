<?php

namespace PegNu\Api\Model;

class Connection extends Model
{
    /**
     * @var Checkpoint
     */
    public $from;

    /**
     * @var Checkpoint
     */
    public $to;

    /**
     * @var string
     */
    public $duration;

    /**
     * @var Service
     */
    public $service;

    /**
     * @var string[]
     */
    public $products;

    /**
     * @var string
     */
    public $capacity1st;

    /**
     * @var string
     */
    public $capacity2nd;

    /**
     * @var Section[]
     */
    public $sections;

    /**
     * Connection constructor.
     *
     * @param Checkpoint $from
     * @param Checkpoint $to
     * @param string     $duration
     * @param Service    $service
     * @param \string[]  $products
     * @param string     $capacity1st
     * @param string     $capacity2nd
     * @param Section[]  $sections
     */
    public function __construct(Checkpoint $from, Checkpoint $to, $duration, Service $service, array $products, $capacity1st, $capacity2nd, array $sections)
    {
        $this->from = $from;
        $this->to = $to;
        $this->duration = $duration;
        $this->service = $service;
        $this->products = $products;
        $this->capacity1st = $capacity1st;
        $this->capacity2nd = $capacity2nd;
        $this->sections = $sections;
    }

    public static function fromJson($connection)
    {
        $sections = [];

        foreach (self::tryGetField($connection, 'sections', []) as $section) {
            $sections[] = Section::fromJson($section);
        }

        return new self(
            Checkpoint::fromJson(self::tryGetField($connection, 'from', [])),
            Checkpoint::fromJson(self::tryGetField($connection, 'to', [])),
            self::tryGetField($connection, 'duration'),
            Service::fromJson(self::tryGetField($connection, 'service', [])),
            self::tryGetField($connection, 'products'),
            self::tryGetField($connection, 'capacity1st'),
            self::tryGetField($connection, 'capacity2nd'),
            $sections
        );
    }
}
