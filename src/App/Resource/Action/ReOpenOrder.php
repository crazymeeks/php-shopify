<?php

declare(strict_types=1);

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class ReOpenOrder extends BaseAction
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
            throw \Crazymeeks\App\Exceptions\BadRequestException::orderIdIsRequired();
        }

        $endpoint = sprintf('/admin/api/%s/orders/%s/open.json', $configContext->getVersion(), $id);
        
        $host .= parent::updateEndpoint($app, $endpoint);
        
        $response = $this->curl->to($host)
                         ->withHeaders([
                            'X-Shopify-Access-Token: ' . $access_token
                         ])
                         ->withResponseHeaders()
                         ->returnResponseObject()
                         ->post();
        
        if ($response->status == 200) {
            $collection = json_decode($response->content);
            return parent::addPaginateLinks($collection, $response);
        }

        throw new \Exception($response->content);
        
    }    

}