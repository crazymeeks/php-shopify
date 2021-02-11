<?php

namespace Crazymeeks\App\Exceptions;

class CustomerException extends \Exception
{
    public static function invalidData()
    {
        return new static("Unable to create customer because data is missing. Please make sure you call setData() method.");
    }

    public static function emailDomainNotAllowed(string $domain)
    {
        return new static(sprintf('The email domain %s is not allowed or not whitelisted. Please contact your app developer.', $domain));
    }
}