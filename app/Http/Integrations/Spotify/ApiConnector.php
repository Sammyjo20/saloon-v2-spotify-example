<?php

namespace App\Http\Integrations\Spotify;

use Saloon\Contracts\HasPagination;
use Saloon\Contracts\Paginator;
use Saloon\Contracts\Request;
use Saloon\Http\Connector;
use Saloon\Http\Paginators\OffsetPaginator;
use Saloon\Traits\Plugins\AcceptsJson;

class ApiConnector extends Connector implements HasPagination
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

    /**
     * Paginate
     *
     * @param \Saloon\Contracts\Request $request
     * @param ...$additionalArguments
     * @return \Saloon\Http\Paginators\OffsetPaginator
     */
    public function paginate(Request $request, ...$additionalArguments): OffsetPaginator
    {
        return OffsetPaginator::make($this, $request, 50);
    }
}
