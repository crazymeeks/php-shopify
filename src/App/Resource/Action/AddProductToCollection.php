<?php

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Support\Str;
use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Exceptions\CollectionException;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class AddProductToCollection extends BaseAction
{

    /**
     * @implemented
     */
    public function doAction(ShopifyConfigContextInterface $configContext, ShopifyApp $app)
    {
        $data = $app->getData();
        $access_token = $app->getAccessToken();
        $host = $app->getShopUrl();

        $host .= !empty($this->getEndpoint()) ? $this->getEndpoint() : sprintf('/admin/api/%s/collects.json', $configContext->getVersion());

        $response = $this->curl->to($host)
                         ->withData($data)
                         ->withHeaders([
                            'X-Shopify-Access-Token: ' . $access_token
                         ])
                        ->returnResponseObject()
                        ->post();
        if ($response->status == 201) {
            $collect = json_decode($response->content);
            return $collect->collect;
        }
        
        throw CollectionException::addProductToCollection($response->content);
    }
    

}