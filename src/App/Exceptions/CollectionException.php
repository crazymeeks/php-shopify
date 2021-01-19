<?php

namespace Crazymeeks\App\Exceptions;

class CollectionException extends \Exception
{
    public static function collectionIdRequired()
    {
        return new static("Collection id is required");
    }

    public static function addProductToCollection($message)
    {
        return new static($message);
    }
}