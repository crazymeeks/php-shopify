<?php

namespace Crazymeeks\App;

class ShopUrl
{

    private $url;

    /**
     * Construct
     * 
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->parseUrl($url);
    }

    private function parseUrl(string $url)
    {

        $found = false;
        foreach(['https', 'http'] as $protocol){
            if (strpos($url, $protocol) !== false) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $url = "https://" . $url;
        }

        $parsed = parse_url($url);
        $this->url = str_replace('www.', '', $parsed['host']);
    }

    public function get(): string
    {
        return "https://" . $this->url;
    }
}