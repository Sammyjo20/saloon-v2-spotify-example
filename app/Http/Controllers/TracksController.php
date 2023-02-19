<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Spotify\Requests\CurrentSongRequest;
use App\Http\Integrations\Spotify\Requests\LikedSongsRequest;
use Illuminate\Http\Request;

class TracksController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \ReflectionException
     * @throws \Saloon\Exceptions\InvalidResponseClassException
     * @throws \Saloon\Exceptions\OAuthConfigValidationException
     * @throws \Saloon\Exceptions\PendingRequestException
     */
    public function __invoke(Request $request)
    {
        // See User.php

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $paginator = $user->spotify()->paginate(new LikedSongsRequest);

        $collection = $paginator->collect('items')
            ->map(function ($track) {
                return $track['track']['artists'][0]['name'] . ' - ' . $track['track']['name'];
            });

        return response()->json($collection);
    }
}
