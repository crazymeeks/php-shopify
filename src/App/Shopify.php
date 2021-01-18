<?php

declare(strict_types=1);

namespace Crazymeeks\App;

use Crazymeeks\App\ShopUrl;
use Crazymeeks\App\Http\Redirect;
use Crazymeeks\App\Contracts\InstallContextInterface;
use Crazymeeks\App\Contracts\ResourceContextInterface;
use Crazymeeks\App\Contracts\ShopifyConfigContextInterface;
use Crazymeeks\App\Contracts\Resource\ActionInterface as ResourceActionInterface;

class Shopify
{

    private $configContext;

    private $redirect;
    /**
     * @var \Crazymeeks\App\Contracts\ResourceContextInterface
     */
    private $resource;

    private $resource_id = null;

    /**
     * @var \Crazymeeks\App\Contracts\Resource\ActionInterface
     */
    private $resource_action;

    /**
     * Location of our overloaded data
     * @var array
     */
    private $data = [];

    public function __construct(
        ShopifyConfigContextInterface $configContext,
        Redirect $redirect
    )
    {
        $this->configContext = $configContext;
        $this->redirect = $redirect;
    }

    /**
     * Install app to shopify. This will perform redirection to
     * user's shopify store account
     * 
     * @param \Crazymeeks\App\Contracts\InstallContextInterface $context
     * @param string $shop_url Shopify url(test.myshopify.com)
     *
     * @return redirect
     */
    public function install(InstallContextInterface $context, string $shop_url)
    {
        $redirect_url = http_build_query([
            'client_id' => $this->configContext->getApiKey(),
            'scope' => implode(',', $context->getScopes()),
            'redirect_uri' => $context->getRedirectUri(),
        ]);

        $shop_url = new ShopUrl($shop_url);

        // Build install/approval URL to redirect to
        $install_url = $shop_url->get() . "/admin/oauth/authorize?$redirect_url";

        $response = $this->redirect->to($install_url);

        return $response;
    }

    /**
     * Set what resource use would want to do
     * 
     * @param \Crazymeeks\App\Contracts\ResourceContextInterface $context
     *
     * @return $this
     */
    public function setResource(ResourceContextInterface $context): self
    {

        $this->resource = $context;

        return $this;
    }

    /**
     * Set resource collection ID.
     * 
     * @param string $id Could be a collection id
     *
     * @return $this
     */
    public function setResourceId(string $id): self
    {
        $this->resource_id = $id;

        return $this;
    }

    /**
     * Get resource collection ID
     *
     * @return string|null
     */
    public function getResourceId()
    {
        return $this->resource_id;
    }

    /**
     * Set action type
     *
     * @param \Crazymeeks\App\Contracts\Resource\ResourceActionInterface $action
     * 
     * @return $this
     */
    public function setAction(ResourceActionInterface $action): self
    {

        $this->resource_action = $action;

        return $this;
    }

    public function execute()
    {
        return $this->resource->execute($this->resource_action, $this->configContext, $this);
    }
}