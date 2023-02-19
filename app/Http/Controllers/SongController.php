<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Spotify\ApiConnector;
use App\Http\Integrations\Spotify\AuthConnector;
use App\Http\Integrations\Spotify\Requests\CurrentSongRequest;
use Illuminate\Http\Request;
use Saloon\Http\Auth\AccessTokenAuthenticator;

class SongController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \ReflectionException
     * @throws \Saloon\Exceptions\InvalidResponseClassException
     * @throws \Saloon\Exceptions\OAuthConfigValidationException
     * @throws \Saloon\Exceptions\PendingRequestException
     */
    public function __invoke()
    {
        // See User.php

        $user = auth()->user();
        $response = $user->spotify()->send(new CurrentSongRequest);

        // Process response

        $track = $response->json('item');

        return view('current-song', ['track' => $track]);
    }
}
