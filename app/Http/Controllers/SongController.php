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
        $user = auth()->user();

        if (blank($user->spotify_auth)) {
            return redirect()->route('spotify.authorize');
        }

        /** @var AccessTokenAuthenticator $auth */
        $auth = $user->spotify_auth;

        if ($auth->hasExpired()) {
            $auth = AuthConnector::make()->refreshAccessToken($auth);

            $user->spotify_auth = $auth;
            $user->save();
        }

        // Fetch the song.

        $request = CurrentSongRequest::make()->authenticate($auth);
        $response = ApiConnector::make()->send($request);

        $track = $response->json('item');

        return view('current-song', ['track' => $track]);
    }
}
