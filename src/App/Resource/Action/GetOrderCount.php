<?php

declare(strict_types=1);

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class GetOrderCount extends BaseAction
{

    


    /**
     * @implemented
     */
    public function doAction(ShopifyConfigContextInterface $configContext, ShopifyApp $app)
    {
        $access_token = $app->getAccessToken();
        $host = $app->getShopUrl();
        $status = $app->getStatus() ? $app->getStatus() : 'any';
        $fin_status = $app->getFinancialStatus() ? $app->getFinancialStatus() : 'any';
        $ffmt_status = $app->getFulfillmentStatus() ? $app->getFulfillmentStatus() : 'any';

        $endpoint = sprintf('/admin/api/%s/orders/count.json?status=%s&financial_status=%s&fulfillment_status=%s', $configContext->getVersion(), $status, $fin_status, $ffmt_status);
        
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