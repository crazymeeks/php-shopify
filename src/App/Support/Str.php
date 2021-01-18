<?php

namespace Crazymeeks\App\Support;

class Str
{

    /**
     * Check if string contains a string
     *
     * @param string $hayhack The subject string
     * @param mixed $needles could also be an array
     * 
     * @return boolean
     */
    public static function contains(string $hayhack, $needles)
    {
        $contains = false;
        foreach((array) $needles as $needle){
            if (strpos($hayhack, $needle) !== false) {
                break;
                $contains = true;
            }
        }

        return $contains;
    }
}