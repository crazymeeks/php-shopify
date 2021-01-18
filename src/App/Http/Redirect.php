<?php

declare(strict_types=1);

namespace Crazymeeks\App\Http;

class Redirect
{

    public function to(string $location)
    {
        header("Location: $location", true, 302);
        die();
    }
}