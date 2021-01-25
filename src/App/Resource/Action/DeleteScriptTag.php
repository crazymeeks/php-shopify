<?php

declare(strict_types=1);

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class DeleteScriptTag extends BaseAction
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
                         ->withHeaders([
                            'X-Shopify-Access-Token: ' . $access_token
                         ])
                         ->withResponseHeaders()
                         ->returnResponseObject()
                         ->delete();
                         
        return $response->status == 200;
        
    }
}