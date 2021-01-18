<?php

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Contracts\ResourceContextInterface;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;
use Crazymeeks\App\Exceptions\ShopAccessTokeRequestException;

class GetShopAccessToken extends BaseAction
{

    /**
     * @implemented
     */
    public function doAction(ShopifyConfigContextInterface $configContext, ResourceContextInterface $resourceContext, ShopifyApp $app)
    {
        $query_params = $resourceContext->getParams();
        // dd($query_params);
        $hmac = $query_params['hmac'];

        $query_params = array_diff_key($query_params, ['hmac' => '']); // Remove hmac from query params
        ksort($query_params);
        
        $computed_hmac = hash_hmac('sha256', http_build_query($query_params), $configContext->getSecretKey());

        // Use hmac data to check if the response is from Shopify or not
        if (hash_equals($hmac, $computed_hmac)) {
            
            // set variables for query request
            $query = [
                'client_id' => $configContext->getApiKey(),
                'client_secret' => $configContext->getSecretKey(),
                'code' => $query_params['code'],
            ];
            
            // Generate access token URL
            $access_token_url = "https://" . $query_params['shop'] . "/admin/oauth/access_token";
            $response = $this->curl->to($access_token_url)
                                   ->withData($query)
                                   ->returnResponseObject()
                                   ->post();
            
            if (in_array($response->status, [200, 201])) {
                $content = json_decode($response->content);
                return $content;
            }
            
            throw new ShopAccessTokeRequestException();
        }
    }

}