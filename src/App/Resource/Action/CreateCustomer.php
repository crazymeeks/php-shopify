<?php

declare(strict_types=1);

namespace Crazymeeks\App\Resource\Action;

use Crazymeeks\App\Shopify as ShopifyApp;
use Crazymeeks\App\Resource\Action\BaseAction;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;


class CreateCustomer extends BaseAction
{

    


    /**
     * @implemented
     */
    public function doAction(ShopifyConfigContextInterface $configContext, ShopifyApp $app)
    {
        $access_token = $app->getAccessToken();
        $host = $app->getShopUrl();
        
        $endpoint = sprintf('/admin/api/%s/customers.json', $configContext->getVersion());
        
        $host .= parent::updateEndpoint($app, $endpoint);
        $customerData = $app->getData();
        $whiteListedEmailDomains = $app->getWhitelistedEmailDomains();
        
        if (!$customerData) {
            throw \Crazymeeks\App\Exceptions\CustomerException::invalidData();
        }
        $domain = '@' . substr(strrchr($customerData['email'], "@"), 1);
        
        if (count($whiteListedEmailDomains) > 0) {
            if (count($whiteListedEmailDomains) != count($whiteListedEmailDomains, COUNT_RECURSIVE)) {
                $whiteListedEmailDomains = $whiteListedEmailDomains[0];
            }
            if (!in_array($domain, $whiteListedEmailDomains)) {
                throw \Crazymeeks\App\Exceptions\CustomerException::emailDomainNotAllowed($domain);
            }
        }
        
        $response = $this->curl->to($host)
                         ->withHeaders([
                            'X-Shopify-Access-Token: ' . $access_token
                         ])
                         ->withData([
                             'customer' => $customerData
                         ])
                         ->withResponseHeaders()
                         ->returnResponseObject()
                         ->post();
        
        if ($response->status == 201) {
            $collection = json_decode($response->content);
            return parent::addPaginateLinks($collection, $response);
        }

        throw new \Exception($response->content);
        
    }    

}