<?php

namespace Crazymeeks\App\Resource;

use Crazymeeks\App\Contracts\ResourceContextInterface;

abstract class BaseResource implements ResourceContextInterface
{
    private $params = [];

    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * @implemented
     */
    public function getParams(): array
    {
        return $this->params;
    }
}