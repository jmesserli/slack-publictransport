<?php
namespace PegNu\Api\Model;

class Journey
{

    /**
     * @var $name string
     */
    public $name;

    /**
     * @var $category string
     */
    public $category;

    /**
     * @var $categoryCode int
     */
    public $categoryCode;

    /**
     * @var $number string
     */
    public $number;

    /**
     * @var $operator string
     */
    public $operator;

    /**
     * @var $to string
     */
    public $to;

    /**
     * @var $passList Checkpoint[]
     */
    public $passList;

    /**
     * @var $capacity1st int
     */
    public $capacity1st;

    /**
     * @var $capacity2nd int
     */
    public $capacity2nd;

    /**
     * Journey constructor.
     * @param string $name
     * @param string $category
     * @param int $categoryCode
     * @param string $number
     * @param string $operator
     * @param string $to
     * @param Checkpoint[] $passList
     * @param int $capacity1st
     * @param int $capacity2nd
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

        foreach ($journey["passList"] as $checkpoint)
            $checkpoints[] = Checkpoint::fromJson($checkpoint);

        return new Journey(
            $journey["name"],
            $journey["category"],
            $journey["categoryCode"],
            $journey["number"],
            $journey["operator"],
            $journey["to"],
            $checkpoints,
            $journey["capacity1st"],
            $journey["capacity2nd"]
        );
    }
}