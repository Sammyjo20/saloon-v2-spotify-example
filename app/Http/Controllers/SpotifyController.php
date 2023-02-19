<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Spotify\AuthConnector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SpotifyController extends Controller
{
    /**
     * Handle the authorization URL redirection.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Saloon\Exceptions\OAuthConfigValidationException
     */
    public function handleAuthorization()
    {
        $connector = new AuthConnector;

        $authorizationUrl = $connector->getAuthorizationUrl(['user-read-currently-playing']);

        Session::put('spotifyAuthState', $connector->getState());

        return redirect()->to($authorizationUrl);
    }

    /**
     * Handle the call back from Spotify.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \ReflectionException
     * @throws \Saloon\Exceptions\InvalidResponseClassException
     * @throws \Saloon\Exceptions\InvalidStateException
     * @throws \Saloon\Exceptions\OAuthConfigValidationException
     * @throws \Saloon\Exceptions\PendingRequestException
     */
    public function handleCallback(Request $request)
    {
        $code = $request->input('code');
        $state = $request->input('state');

        $expectedState = Session::pull('spotifyAuthState');

        $connector = new AuthConnector;

        $authorization = $connector->getAccessToken($code, $state, $expectedState);

        $user = auth()->user();
        $user->spotify_auth = $authorization;
        $user->save();

        return redirect()->route('home');
    }
}
