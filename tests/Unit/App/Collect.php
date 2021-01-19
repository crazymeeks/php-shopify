<?php

namespace Tests\Unit\App;

trait Collect
{

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

    /**
     * @dataProvider collectResult
     *
     * @return void
     */
    public function testGetCollectList($expectedResponse)
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

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\GetCollect($this->curl))
                                  ->setAccessToken('access_token')
                                  ->setShopUrl('test.myshopify.com')
                                  ->execute();
        
        $this->assertObjectHasAttribute('collects', $response);
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

    public function collectResult()
    {
        $expectedResponse = new \stdClass();
        $expectedResponse->content = json_encode([
            'collects' => [
             [
                 'id'=> 358268117,
                 'collection_id'=> 482865238,
                 'product_id'=> 632910392,
                 'created_at'=> null,
                 'updated_at'=> null,
                 'position'=> 1,
                 'sort_value'=> '0000000001'
            ]
            ]
        ]);
        $expectedResponse->status = 200;
        $expectedResponse->headers = ['Link' => '<https://shoppalist.myshopify.com/admin/api/2021-01/collections/239222980791/products.json?limit=1&page_info=eyJkaXJlY3Rpb24iOiJuZXh0IiwibGFzdF9pZCI6NjEzMjYwMjQ3MDU4MywibGFzdF92YWx1ZSI6IjAifQ>; rel="next"'];

        return [
            array($expectedResponse)
        ];
    }
}