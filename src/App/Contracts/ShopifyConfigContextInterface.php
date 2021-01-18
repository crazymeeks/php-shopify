<?php

declare(strict_types=1);

namespace Crazymeeks\App\Contracts;

interface ShopifyConfigContextInterface
{

    /**
     * Shopify apikey
     *
     * @return string
     */
    public function getApiKey(): string;

    /**
     * Shopify secret key
     *
     * @return string
     */
    public function getSecretKey(): string;

    /**
     * Get api version
     *
     * @return string
     */
    public function getVersion(): string;
}