## PHP Shopify
PHP Shopify is a library that provides simple yet Object Oriented way  
of interacting with [Shopify](https://www.shopify.com.ph/).  

## # Requirements
  - PHP 7.x

## # Installation
Copy and paste this in your terminal `composer require crazymeeks/php-shopify`  

## # Available APIs
  - App installation
  - Customer
  - Collection/Collect/Product
  - Order
  - ScriptTag  


## # Configuration/Setup
In order to use this library, a minimal configration is need. We will be creating two class.  
Let's call it `ConfigContext` and `InstallContext`.  
`ConfigContext` must implement `\Crazymeeks\App\Contracts\ShopifyConfigContextInterface` and `InstallContext` must implement `Crazymeeks\App\Contracts\InstallContextInterface`.  
Note: You may name your classes to whatever you want.  
Please follow instruction properly and you'll be good to go!  

1. ConfigContext
```php
namespace Some\Name\Space;

use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class ConfigContext implements ShopifyConfigContextInterface
{
    /**
     * @implement
     */
    public function getApiKey(): string
    {
        return 'your-shopify-api-key-here';
    }

    /**
     * @implement
     */
    public function getSecretKey(): string
    {
        return 'your-shopify-secret-key-here';
    }

    /**
     * @implement
     */
    public function getVersion(): string
    {
        return '2021-01';
    }
}
```  
2. InstallContext  
```php
namespace Your\Name\Space;

use Crazymeeks\App\Contracts\InstallContextInterface;

class InstallContext implements InstallContextInterface
{
    /**
     * @implemented
     */
    public function getScopes(): array
    {
        // You may add as many scopes as you want here
        return [
            'read_orders',
            'write_products',
        ];
    }

    /**
     * @implemented
     */
    public function getRedirectUri(): string
    {
        // The url of your website that will be use by shopify
        // during installation of your shopify app
        return 'https://mywebsite.com/app/generate-token';
    }
}
```  
#

## # Let someone install your app!
In order for your app to communicate with Shopify, it has to be installed on shopify.  
Just instantiate `Crazymeeks\App\Shopify` and call its `install()` method.  
The 1st argument you would need to pass in this method is the instance of `InstallContext`  
you just defined above. The 2nd argument would be the url of your shopify store like `test.myshopify.com`.  
That's it! You will be redirected to your shopify store to continue the installation.  
```php

$shopify = new \Crazymeeks\App\Shopify();

$shopify->install(new InstallContext(), 'test.myshopify.com');

```  
## # Get Access Token
When your app is installed into shopify, shopify will load your app into  
its Dashboard(iframed) along with these important parameters: `hmac`, `code`, `shop`, `timestamp`.  
You need these parameters in order to get an access token for your app.  
You are required to pass this in the `setData()` method of `Crazymeeks\App\Shopify::class`.  
We will set `\Crazymeeks\App\Resource\Action\GetShopAccessToken:class` as our action.
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\GetShopAccessToken())
                    ->setData([
                        'hmac' => 'a3cc315a829340ab014e7f5aa8eabe83f9cfeaf4b9eb6c17f04c85cabf188729',
                        'code' => '6a94694acf0339e9eb8068d8f4718eea',
                        'shop' => 'test.myshopify.com',
                        'timestamp' => '1610955131',
                    ])
                    ->setShopUrl('test.myshopify.com')
                    ->execute();
// echo access_token and scope,
// you must save the response' access token
// for future use
echo 'Access Token: ' . $response->access_token . '<br>Scope: ' . $response->scope; 
```
## # Customer API
Use to manage customer data. Please refer to shopify's [documentation](https://shopify.dev/docs/admin-api/rest/reference) for the response and other details.  
__Create Customer__ - When creating a customer to shopify, you just need to instantiate `\Crazymeeks\App\Resource\Action\CreateCustomer::class`  
and pass it as an argument to `setAction()` of `\Crazymeeks\App\Shopify::class`
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\CreateCustomer())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setData([
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'john.doe@example.com',
                        'verified_email' => true,
                        'send_email_welcome' => false,
                        'password' => 'test123123',
                        'password_confirmation' => 'test123123',
                    ])
                    ->execute();
```  
__Search Customer__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\SearchCustomer())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setData('email:john.doe@example.com')
                    ->execute();
```  
__Retrieve Single Customer__
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\GetCustomer())
                    ->setAccessToken('your-access-token')
                    ->setResourceId('207119551')
                    ->setShopUrl('test.myshopify.com')
                    ->execute();
```  
__Customer email domain whitelisting__  
You may also whitelist the list of email domain you wish to allow when adding/creating a customer.  
For example, if you want to allow only all email with the domain `@gmail.com`,  
just call `setWhitelistedEmailDomains()`. This method accepts an array. Thus,  
you may whitelist as many email domain as you want!  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\GetCustomer())
                    ->setAccessToken('your-access-token')
                    ->setResourceId('207119551')
                    ->setShopUrl('test.myshopify.com')
                    ->setWhitelistedEmailDomains(['@gmail.com', '@hotmail.com'])
                    ->execute();
```  
#

## # Collect API
According to [shopify](https://shopify.dev/docs/admin-api/rest/reference/products/collect), Collects are meant for managing the relationship between products and custom collections.
__Get Collect List__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\GetCollect())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->execute();
```  
__Get Collect By ID__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\GetCollect())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setResourceId('455204334')
                    ->execute();
```  
__Get Collect Count__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\CollectCount())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->execute();
```  
#

## Collection API
According to [shopify](https://shopify.dev/docs/admin-api/rest/reference/products/collection), A collection is a grouping of products that merchants can create to make their stores easier to browse. For example, a merchant might create a collection for a specific type of product that they sell, such as Footwear.  

__Retrieve a list of products belonging to a collection__
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\GetProductCollections())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setResourceId('239222980791')
                    ->setPerPage(10) // Optional only
                    ->execute();
```  
__Retrieve single collection__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\GetCollection())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setResourceId('841564295')
                    ->execute();
```  
__Add Product to a collection__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\AddProductToCollection())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setData([
                        'product_id' => 921728736,
                        'collection_id' => 841564295,
                    ])
                    ->execute();
```  
__Delete Product from collection__
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\DeleteProductToCollection())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setData('455204334')
                    ->execute();
```  
#

## # Orders API
According to [shopify](https://shopify.dev/docs/admin-api/rest/reference/orders), Order API give merchants new ways to receive, process, and manage their orders.  
__Retrieve Orders__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\GetOrder())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setStatus(\Crazymeeks\App\Resource\Action\GetOrder::CANCELLED) // Optional. Other status are: CLOSED, OPEN
                    ->setFinancialStatus(\Crazymeeks\App\Resource\Action\GetOrder::FIN_ANY) // Optional. Other financial status are: FIN_AUTHORIZED, FIN_PENDING, FIN_PAID, FIN_PARTIALLY_PAID, FIN_REFUNDED, FIN_VOIDED, FIN_PARTIALLY_REFUNDED, FIN_UNPAID
                    ->setFulfillmentStatus(\Crazymeeks\App\Resource\Action\GetOrder::FFMT_ANY) // Optional. Other fulfillment status are: FFMT_SHIPPED, FFMT_PARTIAL, FFMT_UNSHIPPED, FFMT_UNFULFILLED
                    ->setPerPage(5) // Optional
                    ->execute();
```  
__Retrieve Single Order__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\GetOrder())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setResourceId('450789469')
                    ->execute();
```  
__Retrieve Order Count__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\GetOrderCount())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->execute();
```  
__Closing an Order__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\CloseOrder())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setResourceId('450789469')
                    ->execute();
```  
__Opening an Order__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\ReOpenOrder())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setResourceId('450789469')
                    ->execute();
```  
__Cancelling an Order__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\CancelOrder())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setResourceId('450789469')
                    ->execute();
```  
#

## # Script Tag API
According to [shopify](https://shopify.dev/docs/admin-api/rest/reference/online-store/scripttag), the ScriptTag resource represents remote JavaScript code that is loaded into the pages of a shop's storefront or the order status page of checkout. This lets you add functionality to those pages without using theme templates.  

__Create Script Tag__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\CreateScriptTag())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setData('https://myweb.com/script.js')
                    ->execute();
```  
__Retrieve Script Tags__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\GetScriptTags())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->execute();
```  
__Update Script Tag__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\UpdateScriptTag())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setResourceId('596726825')
                    ->setData('https://myweb.com/script.js')
                    ->execute();
```  
__Delete Script Tag__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\DeleteScriptTag())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->setResourceId('596726825')
                    ->execute();
```  
__Retreive count of Script Tag__  
```php
$shopify = new \Crazymeeks\App\Shopify(new ConfigContext());
$response = $shopify->setAction(new \Crazymeeks\App\Resource\Action\ScriptTagCount())
                    ->setAccessToken('your-access-token')
                    ->setShopUrl('test.myshopify.com')
                    ->execute();
```  

### Author
Jeff Claud

