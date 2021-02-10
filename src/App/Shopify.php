<?php

declare(strict_types=1);

namespace Crazymeeks\App;

use Crazymeeks\App\Support\Str;
use Crazymeeks\App\ShopUrl;
use Crazymeeks\App\Http\Redirect;
use Crazymeeks\App\Contracts\InstallContextInterface;
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

    private $shop_url;

    private $access_token;

    /**
     * @var \Crazymeeks\App\Contracts\Resource\ActionInterface
     */
    private $resource_action;

    private $data = null;

    private $status = null;

    /**
     * Financial status
     *
     * @var string
     */
    private $fin_status = null;

    /**
     * Fullfillment status
     *
     * @var string
     */
    private $ffmt_status = null;

    /**
     * Per page result of a collections
     *
     * @var null|int
     */
    private $per_page = null;

    /**
     * Amount for cancelling order
     *
     * @var float
     */
    private $amount = 0;

    /**
     * Currency type when cancelling order
     *
     * @var string
     */
    private $currency = 'PHP';

    /**
     * List of blacklisted email domains
     *
     * @var array
     */
    private $blacklisted_email_domains = [];

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

    /**
     * Set shop url
     *
     * @param string $shop_url
     * 
     * @return $this
     */
    public function setShopUrl(string $shop_url): self
    {
        $this->shop_url = $shop_url;

        return $this;
    }

    /**
     * Paginate result of a collection
     * 
     * @param int $per_page
     *
     * @return $this
     */
    public function setPerPage(int $per_page): self
    {
        $this->per_page = $per_page;

        return $this;
    }

    /**
     * Get per page
     *
     * @return mixed
     */
    public function getPerPage()
    {
        return $this->per_page;
    }

    /**
     * Check if pagination is needed
     *
     * @return boolean
     */
    public function hasPerPage(): bool
    {
        return !is_null($this->per_page);
    }

    /**
     * Get shop url
     *
     * @return string
     */
    public function getShopUrl(): string
    {
        $host = $this->shop_url;

        if (!Str::contains($host, ['https', 'http'])) {
            $host = "https://" . $host;
        }

        $parseUrl = parse_url($host);

        $host = "https://" . (str_replace('www.', '', $parseUrl['host']));

        return $host;
    }

    /**
     * Set amount for order cancellation
     * 
     * @param int|float $amount
     * 
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = number_format($amount, 2);

        return $this;
    }

    /**
     * Get amount of order to be cancelled
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set currency of order to be cancelled
     *
     * @param string $currency
     * 
     * @return $this
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency type of order to be cancelled
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set shop access token
     *
     * @return void
     */
    public function setAccessToken(string $token): self
    {
        $this->access_token = $token;

        return $this;
    }

    /**
     * Get shop access token
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    /**
     * Set data
     *
     * @param mixed $value
     * 
     * @return self
     */
    public function setData($value): self
    {
        $this->data = $value;

        return $this;
    }

    /**
     * Set email domains that cannot be allowed to register(customer)
     *
     * @return $this
     */
    public function setBlocklistedEmailDomains(...$args)
    {
        $this->blacklisted_email_domains = $args;

        return $this;
    }

    /**
     * Get list of blacklisted email domains
     *
     * @return array
     */
    public function getBlocklistedEmailDomains(): array
    {
        return $this->blacklisted_email_domains;
    }

    /**
     * Get data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setFinancialStatus(string $fin_status): self
    {
        $this->fin_status = $fin_status;
        return $this;
    }

    public function getFinancialStatus()
    {
        return $this->fin_status;
    }

    public function setFulfillmentStatus(string $ffmt_status): self
    {

        $this->ffmt_status = $ffmt_status;

        return $this;
    }

    public function getFulfillmentStatus()
    {
        return $this->ffmt_status;
    }


    public function execute()
    {

        if ($this->getShopUrl()) {
            return $this->resource_action->doAction($this->configContext, $this);
        }

        throw \Crazymeeks\App\Exceptions\BadRequestException::requiredShopUrlOrAccessToken();
    }
}