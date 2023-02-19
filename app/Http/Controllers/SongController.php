<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Spotify\AuthConnector;
use App\Http\Integrations\Spotify\Requests\CurrentSongRequest;
use Illuminate\Http\Request;
use Sammyjo20\Saloon\Http\Auth\AccessTokenAuthenticator;

class SongController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \ReflectionException
     * @throws \Sammyjo20\Saloon\Exceptions\OAuthConfigValidationException
     * @throws \Sammyjo20\Saloon\Exceptions\SaloonException
     * @throws \Sammyjo20\Saloon\Exceptions\SaloonRequestException
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

        $request = CurrentSongRequest::make()->withAuth($auth);
        $response = $request->send();

        $track = $response->json('item');

        return view('current-song', ['track' => $track]);
    }
}
