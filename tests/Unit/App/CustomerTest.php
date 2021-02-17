<?php

namespace Tests\Unit\App;


use Crazymeeks\App\Shopify;
use Ixudra\Curl\CurlService;
use Crazymeeks\App\Http\Redirect;
use Crazymeeks\App\Contracts\InstallContextInterface;
use Crazymeeks\App\Resource\Action\GetSmartCollections;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;


class CustomerTest extends \Tests\TestCase
{

    private $redirect;
    private $shopify;

    public function setUp(): void
    {
        parent::setUp();
        $this->mockRedirect();
        $this->shopify = new Shopify(new ConfigContext(), $this->redirect);
        $this->curl = \Mockery::mock(CurlService::class);
    }

    private function mockRedirect()
    {
        $this->redirect = \Mockery::mock(Redirect::class);
        $this->redirect->shouldReceive('to')
                       ->with(\Mockery::any())
                       ->andReturn('Redirecting to shopify for installation');
    }

    public function testCreateCustomer()
    {
        
        $this->curl->shouldReceive('to')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withHeaders')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withData')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withResponseHeaders')
                   ->andReturnSelf();
        $this->curl->shouldReceive('returnResponseObject')
                   ->andReturnSelf();
        $this->curl->shouldReceive('post')
                   ->andReturn(json_decode(json_encode([
                       'content' => file_get_contents(__DIR__ . '/_files/customer/create.customer.response.json'),
                       'status' => 201,
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\CreateCustomer($this->curl))
                                  ->setShopUrl('test.myshopify.com')
                                  ->setAccessToken('access_token')
                                  ->setWhitelistedEmailDomains('@example.com')
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
                                  
        $this->assertObjectHasAttribute('customer', $response);
    }

    public function testSearchCustomer()
    {
        
        $this->curl->shouldReceive('to')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withHeaders')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withResponseHeaders')
                   ->andReturnSelf();
        $this->curl->shouldReceive('returnResponseObject')
                   ->andReturnSelf();
        $this->curl->shouldReceive('get')
                   ->andReturn(json_decode(json_encode([
                       'content' => file_get_contents(__DIR__ . '/_files/customer/search.customer.response.json'),
                       'status' => 200,
                   ])));
        
        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\SearchCustomer($this->curl))
                                  ->setShopUrl('test.myshopify.com')
                                  ->setAccessToken('sdhpca_acf15b53853ebe0664fa4d9d874d87d4')
                                  ->setData('email:jefferson.claud@nuworks.ph')
                                  ->execute();
        $this->assertObjectHasAttribute('customers', $response);
    }

    public function testRetrieveSingleCustomer()
    {
        
        $this->curl->shouldReceive('to')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withHeaders')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withResponseHeaders')
                   ->andReturnSelf();
        $this->curl->shouldReceive('returnResponseObject')
                   ->andReturnSelf();
        $this->curl->shouldReceive('get')
                   ->andReturn(json_decode(json_encode([
                       'content' => file_get_contents(__DIR__ . '/_files/customer/get.customer.by.id.response.json'),
                       'status' => 200,
                   ])));
        
        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\GetCustomer($this->curl))
                                  ->setShopUrl('test.myshopify.com')
                                  ->setResourceId('207119551')
                                  ->setAccessToken('sdhpca_acf15b53853ebe0664fa4d9d874d87d4')
                                  ->execute();
        $this->assertObjectHasAttribute('customer', $response);
    }

    

    public function testWhitelistedEmailDomain()
    {
        
        $this->expectException(\Crazymeeks\App\Exceptions\CustomerException::class);
        $this->curl->shouldReceive('to')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withHeaders')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withData')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withResponseHeaders')
                   ->andReturnSelf();
        $this->curl->shouldReceive('returnResponseObject')
                   ->andReturnSelf();
        $this->curl->shouldReceive('post')
                   ->andReturn(json_decode(json_encode([
                       'content' => file_get_contents(__DIR__ . '/_files/customer/create.customer.response.json'),
                       'status' => 200,
                   ])));

        $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\CreateCustomer($this->curl))
                                  ->setShopUrl('test.myshopify.com')
                                  ->setAccessToken('access_token')
                                  ->setWhitelistedEmailDomains('@free.com')
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
    }
    
}