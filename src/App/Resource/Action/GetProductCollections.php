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
    public function doAction(ShopifyConfigContextInterface $configContext, ResourceContextInterface $resourceContext, ShopifyApp $app)
    {
        
        list($host, $access_token) = $resourceContext->getParams();
        
        if (!Str::contains($host, ['https', 'http'])) {
            $host = "https://" . $host;
        }

        $parseUrl = parse_url($host);
        $host = "https://" . (str_replace('www.', '', $parseUrl['host']));

        $this->shouldHaveResourceId($app);

        $endpoint = !empty($this->getEndpoint()) ? $this->getEndpoint() : sprintf('/admin/api/' . $configContext->getVersion() . '/collections/%s/products.json', $app->getResourceId());
        
        $response = $this->curl->to($endpoint)
                               ->withHeaders([
                                   'X-Shopify-Access-Token: ' . $access_token
                               ])
                               ->returnResponseObject()
                               ->get();
        
        if (in_array($response->status, [200])) {
            $collection = json_decode($response->content);
            return $collection->products;
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