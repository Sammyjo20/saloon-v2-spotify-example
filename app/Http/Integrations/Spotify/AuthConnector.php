<?php

namespace App\Http\Integrations\Spotify;

use Sammyjo20\Saloon\Helpers\OAuth2\OAuthConfig;
use Sammyjo20\Saloon\Http\SaloonConnector;
use Sammyjo20\Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Sammyjo20\Saloon\Traits\Plugins\AcceptsJson;

class AuthConnector extends SaloonConnector
{
    use AuthorizationCodeGrant;
    use AcceptsJson;

    /**
     * The Base URL of the API.
     *
     * @return string
     */
    public function defineBaseUrl(): string
    {
        return 'https://accounts.spotify.com';
    }

    /**
     * Define the default OAuth2 Config.
     *
     * @return OAuthConfig
     */
    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId(config('services.spotify.client_id'))
            ->setClientSecret(config('services.spotify.client_secret'))
            ->setRedirectUri(url()->route('spotify.callback'))
            ->setTokenEndpoint('api/token');
    }
}
