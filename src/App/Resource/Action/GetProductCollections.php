<?php

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Support\Str;
use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Exceptions\CollectionException;
use Crazymeeks\App\Contracts\ResourceContextInterface;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class GetProductCollections extends BaseAction
{

    /**
     * @implemented
     */
    public function doAction(ShopifyConfigContextInterface $configContext, ShopifyApp $app)
    {
        
        $host = $app->getShopUrl();
        $access_token = $app->getAccessToken();

        $this->shouldHaveResourceId($app);
        
        $endpoint = sprintf('/admin/api/%s/collections/%s/products.json', $configContext->getVersion(), $app->getResourceId());

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

    private function shouldHaveResourceId(ShopifyApp $app)
    {
        if (!$app->getResourceId()) {
            throw CollectionException::collectionIdRequired();
        }
    }
    


}