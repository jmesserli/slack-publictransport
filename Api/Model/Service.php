<?php

namespace PegNu\Api\Model;

class Service extends Model
{
    /**
     * @var string
     */
    public $regular;

    /**
     * @var string
     */
    public $irregular;

    /**
     * Service constructor.
     *
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
        return new self(
            self::tryGetField($service, 'regular'),
            self::tryGetField($service, 'irregular')
        );
    }
}
