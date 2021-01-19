<?php

namespace Tests\Unit\App;

use Tests\Unit\App\Collect;
use Crazymeeks\App\Shopify;
use Ixudra\Curl\CurlService;
use Crazymeeks\App\Http\Redirect;
use Crazymeeks\App\Contracts\InstallContextInterface;
use Crazymeeks\App\Resource\Action\GetSmartCollections;
use Crazymeeks\App\Resource\Action\GetProductCollections;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;

class ShopifyTest extends \Tests\TestCase
{

    private $shopify;
    private $redirect;
    private $curl;

    use Collect;

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

    public function testInstall()
    {
        $response = $this->shopify->install(new InstallContext(), 'test.myshopify.com');
        $this->assertEquals('Redirecting to shopify for installation', $response);
    }

    /**
     * @dataProvider getAccessTokenParams
     */
    public function testGetShopAccessToken($request)
    {
        $this->curl->shouldReceive('to')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withData')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('returnResponseObject')
                   ->andReturnSelf();
        $this->curl->shouldReceive('post')
                   ->andReturn(json_decode(json_encode([
                       'content' =>json_encode([
                           'access_token' => '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',
                           'scope' => 'read_orders,write_products',
                       ]),
                       'status' => 200,
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\GetShopAccessToken($this->curl))
                                  ->setData($request)
                                  ->execute();

        $this->assertObjectHasAttribute('access_token', $response);
        $this->assertObjectHasAttribute('scope', $response);
    }

    /**
     * @dataProvider smartCollectionResult
     */
    public function testGetSmartCollections($expectedResponse)
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
                   ->andReturn($expectedResponse);
        
        $response = $this->shopify->setAccessToken('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')
                                  ->setShopUrl('shoppalist.myshopify.com')
                                  ->setAction(new GetSmartCollections($this->curl))
                                  ->execute();

        $this->assertObjectHasAttribute('smart_collections', $response);
    }


    /**
     * @dataProvider productCollectionResult
     */
    public function testPaginateProductCollections($expectedResponse)
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
                   ->andReturn($expectedResponse);
        $response = $this->shopify->setAccessToken('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')
                                  ->setShopUrl('shoppalist.myshopify.com')
                                  ->setResourceId('239222980791')
                                  ->setAction(new GetProductCollections($this->curl))
                                  ->setPerPage(3)
                                  ->execute();
                                  $this->assertObjectHasAttribute('products', $response);
        $this->assertObjectHasAttribute('products', $response);
        $this->assertObjectHasAttribute('previous', $response);
        $this->assertObjectHasAttribute('next', $response);


        $expectedResponse->headers = ['Link' => '<https://shoppalist.myshopify.com/admin/api/2021-01/collections/239222980791/products.json?limit=1&page_info=eyJkaXJlY3Rpb24iOiJuZXh0IiwibGFzdF9pZCI6NjEzMjYwMjQ3MDU4MywibGFzdF92YWx1ZSI6IjAifQ>; rel="next"'];

        $this->curl->shouldReceive('get')
                   ->andReturn($expectedResponse);
        $response = $this->shopify->setAccessToken('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')
                                  ->setShopUrl('shoppalist.myshopify.com')
                                  ->setResourceId('239222980791')
                                  ->setAction(new GetProductCollections($this->curl))
                                  ->setPerPage(3)
                                  ->execute();

        $this->assertObjectHasAttribute('products', $response);
        $this->assertObjectHasAttribute('products', $response);
        $this->assertObjectNotHasAttribute('previous', $response);
        $this->assertObjectHasAttribute('next', $response);
        
    }


    public function testShouldThrowWhenTryingToGetProductsWithoutCollectionId()
    {
        $this->expectException(\Crazymeeks\App\Exceptions\CollectionException::class);
        
        $this->shopify->setAccessToken('access_token')
                      ->setShopUrl('test.myshopify.com')
                      ->setAction(new GetProductCollections())
                      ->execute();
    }

    public function getAccessTokenParams()
    {
        $data = [
            'hmac' => 'a3cc315a829340ab014e7f5aa8eabe83f9cfeaf4b9eb6c17f04c85cabf188729',
            'code' => '6a94694acf0339e9eb8068d8f4718eea',
            'shop' => 'test.myshopify.com',
            'timestamp' => '1610955131',
        ];

        return [
            array($data)
        ];
    }

    public function smartCollectionResult()
    {
        $expectedResponse = new \stdClass();

        $expectedResponse->content = json_encode([
            'smart_collections' => [
                [
                    'id' => 239223472311,
                    'handle' => 'jewelry',
                    'title' => 'Jewelry',
                    'updated_at' => '2020-12-15T04:04:14-05:00',
                    'body_html' => '',
                    'published_at' => '2020-12-15T04:04:14-05:00',
                    'sort_order' => 'best-selling',
                    'template_suffix' > '',
                    'disjunctive' => false,
                    'rules' => [
                        [
                            'column' => 'type',
                            'relation' => 'equals',
                            'condition' => 'Watch'
                        ],
                        [                                  
                            'column' => 'type',
                            'relation' => 'equals',
                            'condition' => 'Necklace'
                        ],
                    ],
                    'published_scope' => 'web',
                    'admin_graphql_api_id' => 'gid://shopify/Collection/239223472311'
                ]
            ]
        ]);
        $expectedResponse->headers = ['Link' => '<https://shoppalist.myshopify.com/admin/api/2021-01/collections/239222980791/products.json?limit=1&page_info=eyJkaXJlY3Rpb24iOiJuZXh0IiwibGFzdF9pZCI6NjEzMjYwMjQ3MDU4MywibGFzdF92YWx1ZSI6IjAifQ>; rel="next"'];
        $expectedResponse->status = 200;

        return [
            array($expectedResponse)
        ];
    }

    public function productCollectionResult()
    {
        $expectedResponse = new \stdClass();
        $expectedResponse->content = json_encode([
            'products' => [
                [
                    'id' => 6132602568887,
                    'title' => 'Salmon Shirt',
                    'body_html' => '\u003cp\u003e\u003c\/p\u003e',
                    'vendor' => 'Fashion',
                    'product_type' => 'Shirts',
                    'created_at' => '2020-12-15T03:50:09-05:00',
                    'handle' => 'salmon-shirt',
                    'updated_at' => '2020-12-15T03:50:09-05:00',
                    'published_at' => '2020-12-15T03:50:09-05:00',
                    'template_suffix' => null,
                    'published_scope' => 'web',
                    'tags' => 'Mens',
                    'admin_graphql_api_id' => 'gid://shopify/Collection/239223472311',
                    'options' => [
                        [
                            'id' => 7836837707959,
                            'product_id' => 6132602568887,
                            'name' => 'Title',
                            'position' => 1
                        ]
                    ],
                    'images' => [
                        [
                            'id' => 23031962796215,
                            'product_id' => 6132602568887,
                            'position' => 1,
                            'created_at' => '2020-12-15T03:50:09-05:00',
                            'updated_at' => '2020-12-15T03:50:09-05:00',
                            'alt' => null,
                            'width' => 600,
                            'height' => 736,
                            'src' => 'https://cdn.shopify.com/s/files/1/0523/4789/5991/products/mens-shirt-1.jpg?v=1608022209',
                            'variants' => [

                            ]
                        ]
                    ],
                    'image' => [
                        'id' => 23031962796215,
                        'product_id' => 6132602568887,
                        'position' => 1,
                        'created_at' => '2020-12-15T03:50:09-05:00',
                        'updated_at' => '2020-12-15T03:50:09-05:00',
                        'alt' => null,
                        'width' => 600,
                        'height' => 736,
                        'src' => 'https://cdn.shopify.com/s/files/1/0523/4789/5991/products/mens-shirt-1.jpg?v=1608022209',
                        'variants' => [
                            
                        ]
                    ]
                ]
            ]
        ]);

        $expectedResponse->headers = ['Link' => '<https://shoppalist.myshopify.com/admin/api/2021-01/collections/239222980791/products.json?limit=1&page_info=eyJkaXJlY3Rpb24iOiJwcmV2IiwibGFzdF9pZCI6NjEzMjYwMjQ3MDU4MywibGFzdF92YWx1ZSI6IjAifQ>; rel="previous", <https://shoppalist.myshopify.com/admin/api/2021-01/collections/239222980791/products.json?limit=1&page_info=eyJkaXJlY3Rpb24iOiJuZXh0IiwibGFzdF9pZCI6NjEzMjYwMjQ3MDU4MywibGFzdF92YWx1ZSI6IjAifQ>; rel="next"'];
        $expectedResponse->status = 200;
        return [
            array($expectedResponse)
        ];
    }

}


class ConfigContext implements ShopifyConfigContextInterface
{
    /**
     * Shopify apikey
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return 'f387caf5686aea977d0462df36ce96f7';
    }

    /**
     * Shopify secret key
     *
     * @return string
     */
    public function getSecretKey(): string
    {
        return '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';
    }

    public function getVersion(): string
    {
        return '2021-01';
    }
}

class InstallContext implements InstallContextInterface
{

    /**
     * @implemented
     */
    public function getScopes(): array
    {
        return [
            'read_orders',
            'write_products'
        ];
    }

    /**
     * @implemented
     */
    public function getRedirectUri(): string
    {
        return 'https://mywebsite.com/app/generate-token';
    }
}