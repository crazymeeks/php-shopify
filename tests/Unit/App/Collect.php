<?php

namespace Tests\Unit\App;

trait Collect
{


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

    public function testGetCollectById()
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
                           'collect' => [
                            'id'=> 358268117,
                            'collection_id'=> 482865238,
                            'product_id'=> 632910392,
                            'created_at'=> null,
                            'updated_at'=> null,
                            'position'=> 1,
                            'sort_value'=> '0000000001'
                           ]
                       ]),
                       'status' => 200
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\GetCollect($this->curl))
                                  ->setAccessToken('access_token')
                                  ->setResourceId('455204334')
                                  ->setShopUrl('test.myshopify.com')
                                  ->execute();
        
        $this->assertObjectHasAttribute('collect', $response);
    }

    public function testGetCollectCount()
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
                           'count' => 1
                       ]),
                       'status' => 200
                   ])));

        $response = $this->shopify->setAction(new \Crazymeeks\App\Resource\Action\CollectCount($this->curl))
                                  ->setAccessToken('access_token')
                                  ->setShopUrl('test.myshopify.com')
                                  ->execute();
        
        $this->assertEquals(1, $response);
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