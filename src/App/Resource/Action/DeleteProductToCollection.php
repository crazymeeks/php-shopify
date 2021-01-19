<?php

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Support\Str;
use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Exceptions\CollectionException;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class DeleteProductToCollection extends BaseAction
{

    /**
     * @implemented
     */
    public function doAction(ShopifyConfigContextInterface $configContext, ShopifyApp $app)
    {
        $id = $app->getData();
        $access_token = $app->getAccessToken();
        $host = $app->getShopUrl();

        $host .= !empty($this->getEndpoint()) ? $this->getEndpoint() : sprintf('/admin/api/%s/collects/%s.json', $configContext->getVersion(), $id);
        
        $response = $this->curl->to($host)
                         ->withHeaders([
                            'X-Shopify-Access-Token: ' . $access_token
                         ])
                        ->returnResponseObject()
                        ->delete();
        if ($response->status == 200) {
            return true;
        }

        return false;
        
    }
    

}