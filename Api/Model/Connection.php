<?php

namespace PegNu\Api\Model;

class Connection
{
    /**
     * @var $from Checkpoint
     */
    public $from;

    /**
     * @var $to Checkpoint
     */
    public $to;

    /**
     * @var $duration string
     */
    public $duration;

    /**
     * @var $service Service
     */
    public $service;

    /**
     * @var $products string[]
     */
    public $products;

    /**
     * @var $capacity1st string
     */
    public $capacity1st;

    /**
     * @var $capacity2nd string
     */
    public $capacity2nd;

    /**
     * @var $sections Section[]
     */
    public $sections;

    /**
     * Connection constructor.
     * @param Checkpoint $from
     * @param Checkpoint $to
     * @param string $duration
     * @param Service $service
     * @param \string[] $products
     * @param string $capacity1st
     * @param string $capacity2nd
     * @param Section[] $sections
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

    public static function fromJson($phpArray)
    {
        $sections = [];

        foreach ($phpArray["sections"] as $section)
            $sections[] = Section::fromJson($section);

        return new Connection(
            Checkpoint::fromJson($phpArray["from"]),
            Checkpoint::fromJson($phpArray["to"]),
            $phpArray["duration"],
            Service::fromJson($phpArray["service"]),
            $phpArray["products"],
            $phpArray["capacity1st"],
            $phpArray["capacity2nd"],
            $sections
        );
    }
}