<?php

declare(strict_types=1);

namespace Crazymeeks\App\Resource\Action;

use Ixudra\Curl\CurlService;
use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Contracts\Resource\ActionInterface;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

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

    

    /**
     * Add page links to result
     *
     * @param \stdClass $collection
     * @param \stdClass $response
     * 
     * @return Object
     */
    public function addPaginateLinks(\stdClass $collection, \stdClass $response)
    {
        if (isset($response->headers['Link'])) {
            $page_links = explode(',', $response->headers['Link']);
            list($prevLink) = explode(';', str_replace('>', '', (str_replace('<', '', $page_links[0]))));
            $nextLink = null;
            if (count($page_links) > 1) {
                list($nextLink) = explode(';', str_replace('>', '', (str_replace('<', '', $page_links[1]))));
                $collection->previous = $prevLink;
                $collection->next = $nextLink;
            } else {
                $collection->next = $prevLink;
            }
        }

        return $collection;
    }

    /**
     * Update endpoint if ever user wants a paginated result
     *
     * @param \Crazymeeks\App\Shopify $app
     * @param string $endpoint
     * 
     * @return string
     */
    public function updateEndpoint(\Crazymeeks\App\Shopify $app, string $endpoint): string
    {
        if ($app->hasPerPage()) {
            $endpoint .= "?limit=" . $app->getPerPage();
        }

        return !empty($this->getEndpoint()) ? $this->getEndpoint() : $endpoint;
    }
}