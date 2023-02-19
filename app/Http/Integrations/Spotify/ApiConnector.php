<?php

namespace App\Http\Integrations\Spotify;

use Sammyjo20\Saloon\Http\SaloonConnector;
use Sammyjo20\Saloon\Traits\Plugins\AcceptsJson;

class ApiConnector extends SaloonConnector
{
    use AcceptsJson;

    /**
     * The Base URL of the API.
     *
     * @return string
     */
    public function defineBaseUrl(): string
    {
        return 'https://api.spotify.com/v1';
    }
}
