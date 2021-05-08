<?php

namespace Tests\Unit\App;


use Crazymeeks\App\Shopify;
use Ixudra\Curl\CurlService;
use Crazymeeks\App\Http\Redirect;
use Crazymeeks\App\Contracts\InstallContextInterface;
use Crazymeeks\App\Resource\Action\GetSmartCollections;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;


class OrderTest extends \Tests\TestCase
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

    public function testGetOrders()
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
        $this->curl->shouldReceive('get')
                   ->andReturn(json_decode(json_encode([
                       'content' => file_get_contents(__DIR__ . '/_files/get.orders.response.json'),
                       'status' => 200,
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\GetOrder($this->curl))
                                  ->setStatus(\Crazymeeks\App\Resource\Action\GetOrder::CANCELLED)
                                  ->setFinancialStatus(\Crazymeeks\App\Resource\Action\GetOrder::FIN_ANY)
                                  ->setFulfillmentStatus(\Crazymeeks\App\Resource\Action\GetOrder::FFMT_ANY)
                                  ->setPerPage(4)
                                  ->setShopUrl('test.myshopify.com')
                                  ->setAccessToken('access_token')
                                  ->execute();
        $this->assertObjectHasAttribute('orders', $response);
    }

    public function testRetrieveSingleOrder()
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
        $this->curl->shouldReceive('get')
                   ->andReturn(json_decode(json_encode([
                       'content' => file_get_contents(__DIR__ . '/_files/get.single.order.response.json'),
                       'status' => 200,
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\GetOrder($this->curl))
                                  ->setResourceId('450789469')
                                  ->setShopUrl('test.myshopify.com')
                                  ->setAccessToken('access_token')
                                  ->execute();
        $this->assertObjectHasAttribute('order', $response);
    }

    public function testRetrieveOrderCount()
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
        $this->curl->shouldReceive('get')
                   ->andReturn(json_decode(json_encode([
                       'content' => json_encode([
                           'count' => 1,
                       ]),
                       'status' => 200,
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\GetOrderCount($this->curl))
                                  ->setShopUrl('test.myshopify.com')
                                  ->setAccessToken('access_token')
                                  ->execute();
        $this->assertSame(1, $response->count);
    }

    public function testCloseOrder()
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
                       'content' => file_get_contents(__DIR__ . '/_files/post.close.order.response.json'),
                       'status' => 200,
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\CloseOrder($this->curl))
                                  ->setResourceId('450789469')
                                  ->setShopUrl('test.myshopify.com')
                                  ->setAccessToken('access_token')
                                  ->execute();
        $this->assertObjectHasAttribute('order', $response);
    }

    public function testOpenOrder()
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
                       'content' => file_get_contents(__DIR__ . '/_files/post.close.order.response.json'),
                       'status' => 200,
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\ReOpenOrder($this->curl))
                                  ->setResourceId('450789469')
                                  ->setShopUrl('test.myshopify.com')
                                  ->setAccessToken('access_token')
                                  ->execute();
        $this->assertObjectHasAttribute('order', $response);
    }

    public function testCancelOrder()
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
                       'content' => file_get_contents(__DIR__ . '/_files/post.close.order.response.json'),
                       'status' => 200,
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\CancelOrder($this->curl))
                                  ->setResourceId('450789469')
                                  ->setShopUrl('test.myshopify.com')
                                  ->setAccessToken('access_token')
                                  ->execute();
        $this->assertObjectHasAttribute('order', $response);
    }
}