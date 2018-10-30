<?php


namespace RandomState\Factory\Exceptions;


use Exception;

class CannotFindUpdaterFactoryForItemWithData extends Exception
{
    public function __construct($item, array $data)
    {
        parent::__construct(json_encode(['class' => get_class($item), 'data' => json_encode($data)]));
    }
}