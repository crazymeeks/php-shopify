<?php

declare(strict_types=1);

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class GetSmartCollections extends BaseAction
{

    /**
     * @implemented
     */
    public function doAction(ShopifyConfigContextInterface $configContext, ShopifyApp $app)
    {
        $host = $app->getShopUrl();
        $access_token = $app->getAccessToken();

        $endpoint = sprintf('/admin/api/%s/smart_collections.json', $configContext->getVersion());

        $host .= parent::updateEndpoint($app, $endpoint);
        
        $response = $this->curl->to($host)
                               ->withHeaders([
                                   'X-Shopify-Access-Token: ' . $access_token
                               ])
                               ->withResponseHeaders()
                               ->returnResponseObject()
                               ->get();
        
        if (in_array($response->status, [200])) {
            $collection = json_decode($response->content);
            return parent::addPaginateLinks($collection, $response);
        }
        return json_decode(json_encode([]));
    }


}