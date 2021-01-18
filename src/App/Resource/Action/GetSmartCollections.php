<?php

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Support\Str;
use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Contracts\ResourceContextInterface;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class GetSmartCollections extends BaseAction
{

    /**
     * @implemented
     */
    public function doAction(ShopifyConfigContextInterface $configContext, ResourceContextInterface $resourceContext, ShopifyApp $app)
    {
        
        list($host, $access_token) = $resourceContext->getParams();
        
        if (!Str::contains($host, ['https', 'http'])) {
            $host = "https://" . $host;
        }

        $parseUrl = parse_url($host);
        $host = "https://" . (str_replace('www.', '', $parseUrl['host']));
        
        $endpoint = !empty($this->getEndpoint()) ? $this->getEndpoint() : '/admin/api/' . $configContext->getVersion() . '/smart_collections.json';
        
        $response = $this->curl->to($endpoint)
                               ->withHeaders([
                                   'X-Shopify-Access-Token: ' . $access_token
                               ])
                               ->returnResponseObject()
                               ->get();
        if (in_array($response->status, [200])) {
            $collection = json_decode($response->content);
            return $collection->smart_collections;
        }
        return json_decode(json_encode([]));
    }


}