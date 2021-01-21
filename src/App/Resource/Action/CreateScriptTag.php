<?php

declare(strict_types=1);

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class CreateScriptTag extends BaseAction
{

    /**
     * @implemented
     */
    public function doAction(ShopifyConfigContextInterface $configContext, ShopifyApp $app)
    {
        $access_token = $app->getAccessToken();
        $host = $app->getShopUrl();

        $endpoint = sprintf('/admin/api/%s/script_tags.json', $configContext->getVersion());

        $host .= parent::updateEndpoint($app, $endpoint);

        $response = $this->curl->to($host)
                         ->withData([
                             'script_tag' => [
                                 'event' => 'onload',
                                 'src' => $app->getData()
                             ]
                         ])
                         ->withHeaders([
                            'X-Shopify-Access-Token: ' . $access_token
                         ])
                         ->withResponseHeaders()
                         ->returnResponseObject()
                         ->post();
        if ($response->status == 201) {
            $collection = json_decode($response->content);
            
            return parent::addPaginateLinks($collection, $response);
        }

        return json_decode(json_encode([]));
        
    }
}