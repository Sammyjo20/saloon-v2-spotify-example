<?php

namespace App\Http\Integrations\Spotify\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class CurrentSongRequest extends Request
{
    /**
     * The HTTP verb the request will use.
     *
     * @var Method
     */
    protected Method $method = Method::GET;

    /**
     * The endpoint of the request.
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/me/player/currently-playing';
    }
}
