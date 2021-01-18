<?php

namespace Tests;


abstract class TestCase extends \PHPUnit\Framework\TestCase
{


    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}