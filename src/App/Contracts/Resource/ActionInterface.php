<?php

namespace Crazymeeks\App\Contracts\Resource;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Contracts\ResourceContextInterface;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

interface ActionInterface
{

    /**
     * Do action
     *
     * @param \Crazymeeks\App\Contracts\ShopifyConfigContextInterface $configContext
     * @param \Crazymeeks\App\Contracts\ResourceContextInterface $resourceContext
     * @param \Crazymeeks\App\Shopify $app
     * 
     * @return mixed
     */
    public function doAction(ShopifyConfigContextInterface $configContext, ResourceContextInterface $resourceContext, ShopifyApp $app);

    /**
     * Set shopify's api endpoint for the action.
     * This should not contain domain
     *
     * @param string $endpoint
     * 
     * @return $this
     */
    public function setEndpoint(string $endpoint): self;

    /**
     * Get shopify's api endpoint
     *
     * @return string
     */
    public function getEndpoint(): string;
}