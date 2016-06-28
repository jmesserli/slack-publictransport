<?php
namespace PegNu\Api\Model;

abstract class Model
{
    abstract static function fromJson($string);

    protected static function tryGetField($array, $field, $default = null)
    {
        return isset($array[$field]) ? $array[$field] : $default;
    }
}