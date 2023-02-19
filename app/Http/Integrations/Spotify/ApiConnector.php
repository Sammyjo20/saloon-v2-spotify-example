<?php

namespace App\Http\Integrations\Spotify;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class ApiConnector extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API.
     *
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.spotify.com/v1';
    }
}
