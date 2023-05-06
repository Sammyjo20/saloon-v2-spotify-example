<?php

namespace App\Http\Integrations\Spotify;

use Saloon\Contracts\Response;
use Sammyjo20\SaloonPagination\Paginators\OffsetPaginator;

class SpotifyPaginator extends OffsetPaginator
{
    protected function isLastPage(Response $response): bool
    {
        return $this->totalResults === $response->json('total');
    }

    protected function getPageItems(Response $response): array
    {
        return $response->json('items', []);
    }
}
