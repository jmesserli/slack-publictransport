<?php
namespace PegNu\Api\Model;

class Service
{
    /**
     * @var $regular string
     */
    public $regular;

    /**
     * @var $irregular string
     */
    public $irregular;

    /**
     * Service constructor.
     * @param string $regular
     * @param string $irregular
     */
    public function __construct($regular, $irregular)
    {
        $this->regular = $regular;
        $this->irregular = $irregular;
    }

    public static function fromJson($service)
    {
        return new Service(
            $service["regular"],
            $service["irregular"]
        );
    }
}