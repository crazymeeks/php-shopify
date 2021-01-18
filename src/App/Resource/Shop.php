<?php

namespace Crazymeeks\App\Resource;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\BaseResource;
use Crazymeeks\App\Contracts\Resource\ActionInterface;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class Shop extends BaseResource
{

    /**
     * @implemented
     */
    public function execute(ActionInterface $action, ShopifyConfigContextInterface $configContext, ShopifyApp $app)
    {
        return $action->doAction($configContext, $this, $app);
    }

}