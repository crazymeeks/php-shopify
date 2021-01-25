<?php

declare(strict_types=1);

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class GetOrder extends BaseAction
{

    const OPEN = 'open';
    const CLOSED = 'closed';
    const CANCELLED = 'cancelled';
    const ANY = 'any';

    // Financial status
    const FIN_AUTHORIZED = 'authorized';
    const FIN_PENDING = 'pending';
    const FIN_PAID = 'paid';
    const FIN_PARTIALLY_PAID = 'partially_paid';
    const FIN_REFUNDED = 'refunded';
    const FIN_VOIDED = 'voided';
    const FIN_PARTIALLY_REFUNDED = 'partially_refunded';
    const FIN_ANY = 'any';
    const FIN_UNPAID = 'unpaid';
    
    // Fulfillment status
    const FFMT_SHIPPED = 'shipped';
    const FFMT_PARTIAL = 'partial';
    const FFMT_UNSHIPPED = 'unshipped';
    const FFMT_ANY = 'any';
    const FFMT_UNFULFILLED = 'unfulfilled';
    
    protected $status = self::ANY;
    protected $fin_status = self::FIN_ANY;
    protected $ffmt_status = self::FFMT_ANY;


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
        $id = $app->getResourceId();

        $endpoint = $id ? sprintf('/admin/api/%s/orders/%s.json', $configContext->getVersion(), $id) : sprintf('/admin/api/%s/orders.json?status=%s&financial_status=%s&fulfillment_status=%s', $configContext->getVersion(), $status, $fin_status, $ffmt_status);
        
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