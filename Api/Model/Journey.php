<?php

namespace PegNu\Api\Model;

class Journey
{
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
    public $categoryCode;

    /**
     * @var string
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
     * @var Checkpoint[]
     */
    public $passList;

    /**
     * @var int
     */
    public $capacity1st;

    /**
     * @var int
     */
    public $capacity2nd;

    /**
     * Journey constructor.
     *
     * @param string       $name
     * @param string       $category
     * @param int          $categoryCode
     * @param string       $number
     * @param string       $operator
     * @param string       $to
     * @param Checkpoint[] $passList
     * @param int          $capacity1st
     * @param int          $capacity2nd
     */
    public function __construct($name, $category, $categoryCode, $number, $operator, $to, array $passList, $capacity1st, $capacity2nd)
    {
        $this->name = $name;
        $this->category = $category;
        $this->categoryCode = $categoryCode;
        $this->number = $number;
        $this->operator = $operator;
        $this->to = $to;
        $this->passList = $passList;
        $this->capacity1st = $capacity1st;
        $this->capacity2nd = $capacity2nd;
    }

    public static function fromJson($journey)
    {
        $checkpoints[] = [];

        foreach ($journey['passList'] as $checkpoint) {
            $checkpoints[] = Checkpoint::fromJson($checkpoint);
        }

        return new self(
            $journey['name'],
            $journey['category'],
            $journey['categoryCode'],
            $journey['number'],
            $journey['operator'],
            $journey['to'],
            $checkpoints,
            $journey['capacity1st'],
            $journey['capacity2nd']
        );
    }
}
