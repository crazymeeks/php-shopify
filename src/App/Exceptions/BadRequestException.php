<?php

declare(strict_types=1);

namespace Crazymeeks\App\Exceptions;

class BadRequestException extends \Exception
{

    public static function requiredShopUrlOrAccessToken()
    {
        return new static("Access token or shop url is required.");
    }

    public static function collectionIdIsRequired()
    {
        return new static("Collection id is required.");
    }

    public static function scriptTagIdIsRequired()
    {
        return new static("Script tag id is required.");
    }
}