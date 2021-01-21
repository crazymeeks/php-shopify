<?php

namespace Tests\Unit\App;

use Crazymeeks\App\Resource\Action\GetProductCollections;

trait Collection
{


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


    /**
     * @dataProvider addProductToCollection
     */
    public function testAddProductToCollection($data)
    {
        $this->curl->shouldReceive('to')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withData')
                   ->with($data)
                   ->andReturnSelf();
        $this->curl->shouldReceive('withHeaders')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('returnResponseObject')
                   ->andReturnSelf();
        $this->curl->shouldReceive('post')
                   ->andReturn(json_decode(json_encode([
                       'content' => json_encode([
                           'collect' => [
                                'id' => 1071559575,
                                'collection_id' => 841564295,
                                'product_id' => 921728736,
                                'created_at' => '2021-01-01T14:19:45-05:00',
                                'updated_at' => '2021-01-01T14:19:45-05:00',
                                'position'=> 2,
                                'sort_value'=> '0000000002'
                           ]
                       ]),
                       'status' => 201
                   ])));
        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\AddProductToCollection($this->curl))
                                  ->setData($data)
                                  ->setAccessToken('access_token')
                                  ->setShopUrl('test.myshopify.com')
                                  ->execute();
        $this->assertObjectHasAttribute('id', $response);
    }

    public function testDeleteProductFromCollection()
    {

        $this->curl->shouldReceive('to')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('withHeaders')
                   ->with(\Mockery::any())
                   ->andReturnSelf();
        $this->curl->shouldReceive('returnResponseObject')
                   ->andReturnSelf();
        $this->curl->shouldReceive('delete')
                   ->andReturn(json_decode(json_encode([
                       'status' => 200
                   ])));
        
        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\DeleteProductToCollection($this->curl))
                                  ->setData('455204334')
                                  ->setAccessToken('access_token')
                                  ->setShopUrl('test.myshopify.com')
                                  ->execute();
        $this->assertTrue($response);
    }

    public function testRetrieveSingleCollection()
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
                       'content' => json_encode([
                           'collection' => [
                                'id' => 841564295,
                                'handle' => 'ipods',
                                'title' => 'IPods',
                                'updated_at' => '2008-02-01T19:00:00-05:00',
                                'body_html' => '<p>The best selling ipod ever</p>',
                                'published_at' => '2008-02-01T19:00:00-05:00',
                                'sort_order' => 'manual',
                                'template_suffix' => null,
                                'products_count' => 1,
                                'collection_type' => 'custom',
                                'published_scope' => 'web',
                                'admin_graphql_api_id' => 'gid =>//shopify/Collection/841564295',
                                'image' => [
                                    'created_at' => '2021-01-01T14:22:39-05:00',
                                    'alt' => 'iPod Nano 8gb',
                                    'width' => 123,
                                    'height' => 456,
                                    'src' => 'https://cdn.shopify.com/s/files/1/0006/9093/3842/collections/ipod_nano_8gb.jpg?v=1609528959'
                                ]
                           ]
                       ]),
                       'status' => 200
                   ])));
        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\GetCollection($this->curl))
                                  ->setAccessToken('access_token')
                                  ->setShopUrl('test.myshopify.com')
                                  ->setResourceId('841564295')
                                  ->execute();
        $this->assertObjectHasAttribute('collection', $response);
    }

    public function testThrowIfNoResourceIdWhenGettingCollection()
    {
        $this->expectException(\Crazymeeks\App\Exceptions\BadRequestException::class);
        $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\GetCollection($this->curl))
                    ->setAccessToken('access_token')
                    ->setShopUrl('test.myshopify.com')
                    ->execute();
    }

    public function addProductToCollection()
    {
        $data = [
            'product_id' => 921728736,
            'collection_id' => 841564295,
        ];

        return [
            array($data)
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