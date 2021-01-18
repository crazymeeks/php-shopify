<?php

namespace Crazymeeks\App\Resource\Action;

use Ixudra\Curl\CurlService;
use Crazymeeks\App\Contracts\Resource\ActionInterface;

abstract class BaseAction implements ActionInterface
{


    /**
     * @var \Ixudra\Curl\CurlService
     */
    protected $curl;

    /**
     * Shopify's api endpoint for this action
     *
     * @var string
     */
    protected $endpoint = '';


    public function __construct(CurlService $curl = null)
    {
        $this->curl = $curl ?? new CurlService();
    }

    /**
     * @implemented
     */
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @implemented
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }
}