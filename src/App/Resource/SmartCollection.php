<?php

namespace Crazymeeks\App\Resource;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\BaseResource;
use Crazymeeks\App\Contracts\Resource\ActionInterface;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class SmartCollection extends BaseResource
{
    
    /**
     * Make a REST request to shopify's api resource
     *
     * @param \Crazymeeks\App\Contracts\Resource\ActionInterface $action
     * @param \Crazymeeks\App\Contracts\ShopifyConfigContextInterface $configContext
     * 
     * @return mixed
     */
    public function execute(ActionInterface $action, ShopifyConfigContextInterface $configContext, ShopifyApp $app)
    {
        return $action->doAction($configContext, $this, $app);
    }
}