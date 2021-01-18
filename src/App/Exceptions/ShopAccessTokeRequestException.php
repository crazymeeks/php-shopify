<?php

namespace Crazymeeks\App\Exceptions;

class ShopAccessTokeRequestException extends \Exception
{
    public function __construct()
    {
        parent::__construct("400 - Oauth error invalid_request");
    }
}