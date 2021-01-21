<?php

namespace Tests\Unit\App;


use Crazymeeks\App\Shopify;
use Ixudra\Curl\CurlService;
use Crazymeeks\App\Http\Redirect;
use Crazymeeks\App\Contracts\InstallContextInterface;
use Crazymeeks\App\Resource\Action\GetSmartCollections;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;


class ScriptTagTest extends \Tests\TestCase
{

    private $redirect;

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

    public function testCreateScript()
    {
        $this->curl->shouldReceive('to')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withHeaders')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withData')
                   ->with([
                       'script_tag' => [
                           'event' => 'onload',
                           'src' => 'https://myweb.com/script.js'
                       ]
                   ])
                   ->andReturnSelf();
        $this->curl->shouldReceive('withResponseHeaders')
                   ->andReturnSelf();
        $this->curl->shouldReceive('returnResponseObject')
                   ->andReturnSelf();
        $this->curl->shouldReceive('post')
                   ->andReturn(json_decode(json_encode([
                       'content' =>json_encode([
                           'script_tag' => [
                                'id' => 870402687,
                                'src' => 'https://djavaskripped.org/fancy.js',
                                'event' => 'onload',
                                'created_at' => '2021-01-01T14:53:20-05:00',
                                'updated_at' => '2021-01-01T14:53:20-05:00',
                                'display_scope' => 'all',
                                'cache' => false
                           ]
                       ]),
                       'status' => 201,
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\CreateScriptTag($this->curl))
                                  ->setData('https://myweb.com/script.js')
                                  ->setShopUrl('test.myshopify.com')
                                  ->setAccessToken('access_token')
                                  ->execute();
        $this->assertObjectHasAttribute('script_tag', $response);
    }

    public function testGetScriptTags()
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
                       'content' =>json_encode([
                           'script_tags' => [
                               [
                                   'id' => 870402687,
                                   'src' => 'https://djavaskripped.org/fancy.js',
                                   'event' => 'onload',
                                   'created_at' => '2021-01-01T14:53:20-05:00',
                                   'updated_at' => '2021-01-01T14:53:20-05:00',
                                   'display_scope' => 'all',

                               ]
                           ]
                       ]),
                       'status' => 200,
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\GetScriptTags($this->curl))
                                  ->setData('https://myweb.com/script.js')
                                  ->setShopUrl('test.myshopify.com')
                                  ->setAccessToken('access_token')
                                  ->execute();
        
        $this->assertObjectHasAttribute('script_tags', $response);
    }

    public function testUpdateScript()
    {
        $this->curl->shouldReceive('to')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withHeaders')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withData')
                   ->with([
                    'script_tag' => [
                        'id' => '596726825',
                        'src' => 'https://myweb.com/script.js',
                    ]
                ])
                   ->andReturnSelf();
        $this->curl->shouldReceive('withResponseHeaders')
                   ->andReturnSelf();
        $this->curl->shouldReceive('returnResponseObject')
                   ->andReturnSelf();
        $this->curl->shouldReceive('post')
                   ->andReturn(json_decode(json_encode([
                       'content' =>json_encode([
                           'script_tag' => [
                                'id' => 870402687,
                                'src' => 'https://djavaskripped.org/fancy.js',
                                'event' => 'onload',
                                'created_at' => '2021-01-01T14:53:20-05:00',
                                'updated_at' => '2021-01-01T14:53:20-05:00',
                                'display_scope' => 'all',
                                'cache' => false
                           ]
                       ]),
                       'status' => 201,
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\UpdateScriptTag($this->curl))
                                  ->setData('https://myweb.com/script.js')
                                  ->setResourceId('596726825')
                                  ->setShopUrl('test.myshopify.com')
                                  ->setAccessToken('access_token')
                                  ->execute();
        $this->assertObjectHasAttribute('script_tag', $response);
    }
}