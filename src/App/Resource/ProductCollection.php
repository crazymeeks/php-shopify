<?php

namespace Crazymeeks\App\Resource;

use Crazymeeks\App\Shopify;
use Crazymeeks\App\Resource\BaseResource;
use Crazymeeks\App\Contracts\Resource\ActionInterface;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class ProductCollection extends BaseResource
{
    
    /**
     * Make a REST request to shopify's api resource
     *
     * @param \Crazymeeks\App\Contracts\Resource\ActionInterface $action
     * @param \Crazymeeks\App\Contracts\ShopifyConfigContextInterface $configContext
     * 
     * @return mixed
     */
    public function execute(ActionInterface $action, ShopifyConfigContextInterface $configContext, Shopify $app)
    {
        return $action->doAction($configContext, $this, $app);
    }
}