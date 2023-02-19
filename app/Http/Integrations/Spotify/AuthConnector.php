<?php

namespace App\Http\Integrations\Spotify;

use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Connector;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Traits\Plugins\AcceptsJson;

class AuthConnector extends Connector
{
    use AuthorizationCodeGrant;
    use AcceptsJson;

    /**
     * The Base URL of the API.
     *
     * @return string
     */
    public function resolveBaseUrl(): string
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
