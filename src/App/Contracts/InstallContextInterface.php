<?php

declare(strict_types=1);

namespace Crazymeeks\App\Contracts;

/**
 * Contract configuration for app installation
 */

interface InstallContextInterface
{

    /**
     * Scopes permission that will be requested to shopify
     *
     * @return array
     */
    public function getScopes(): array;

    /**
     * The url where shopify will redirect after user
     * authorize the installation of the app
     *
     * @return string
     */
    public function getRedirectUri(): string;
}