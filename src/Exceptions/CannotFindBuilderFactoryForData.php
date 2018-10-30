<?php


namespace RandomState\Factory\Exceptions;


use Exception;

class CannotFindBuilderFactoryForData extends Exception
{
    public function __construct(array $data)
    {
        parent::__construct(json_encode($data));
    }
}