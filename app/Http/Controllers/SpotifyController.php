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
     * @throws \Sammyjo20\Saloon\Exceptions\OAuthConfigValidationException
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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \ReflectionException
     * @throws \Sammyjo20\Saloon\Exceptions\InvalidStateException
     * @throws \Sammyjo20\Saloon\Exceptions\OAuthConfigValidationException
     * @throws \Sammyjo20\Saloon\Exceptions\SaloonException
     * @throws \Sammyjo20\Saloon\Exceptions\SaloonRequestException
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
