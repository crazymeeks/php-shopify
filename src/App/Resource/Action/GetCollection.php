<?php

declare(strict_types=1);

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class GetCollection extends BaseAction
{

    /**
     * @implemented
     */
    public function doAction(ShopifyConfigContextInterface $configContext, ShopifyApp $app)
    {
        $access_token = $app->getAccessToken();
        $host = $app->getShopUrl();

        $id = $app->getResourceId();

        if (!$id) {
            throw \Crazymeeks\App\Exceptions\BadRequestException::collectionIdIsRequired();
        }

        $endpoint = sprintf('/admin/api/%s/collections/%s.json', $configContext->getVersion(), $id);
        
        $host .= parent::updateEndpoint($app, $endpoint);

        $response = $this->curl->to($host)
                         ->withHeaders([
                            'X-Shopify-Access-Token: ' . $access_token
                         ])
                         ->withResponseHeaders()
                         ->returnResponseObject()
                         ->get();

        if ($response->status == 200) {
            $collection = json_decode($response->content);
            return parent::addPaginateLinks($collection, $response);
        }

        return json_decode(json_encode([]));
        
    }
    

}