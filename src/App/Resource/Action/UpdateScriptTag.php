<?php

declare(strict_types=1);

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class UpdateScriptTag extends BaseAction
{

    /**
     * @implemented
     */
    public function doAction(ShopifyConfigContextInterface $configContext, ShopifyApp $app)
    {
        $access_token = $app->getAccessToken();
        $host = $app->getShopUrl();

        $id = $app->getResourceId();
        $data = $app->getData();

        
        if (!$id) {
            throw \Crazymeeks\App\Exceptions\BadRequestException::scriptTagIdIsRequired();
        }

        $endpoint = sprintf('/admin/api/%s/script_tags/%s.json', $configContext->getVersion(), $id);

        $host .= parent::updateEndpoint($app, $endpoint);

        $response = $this->curl->to($host)
                         ->withData([
                             'script_tag' => [
                                 'id' => $id,
                                 'src' => $data
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