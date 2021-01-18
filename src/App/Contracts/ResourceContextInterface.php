<?php

namespace Crazymeeks\App\Contracts;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Contracts\Resource\ActionInterface;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

interface ResourceContextInterface
{

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParams(): array;

    /**
     * Make a REST request to shopify's api resource
     *
     * @param \Crazymeeks\App\Contracts\Resource\ActionInterface $action
     * @param \Crazymeeks\App\Contracts\ShopifyConfigContextInterface $configContext
     * @param \Crazymeeks\App\Shopify $app
     * 
     * @return mixed
     */
    public function execute(ActionInterface $action, ShopifyConfigContextInterface $configContext, ShopifyApp $app);
}