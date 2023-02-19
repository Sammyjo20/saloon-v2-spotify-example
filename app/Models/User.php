<?php

namespace App\Models;

use App\Casts\Serialized;
use App\Http\Integrations\Spotify\ApiConnector;
use App\Http\Integrations\Spotify\AuthConnector;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Saloon\Http\Auth\AccessTokenAuthenticator;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'spotify_auth',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'spotify_auth',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'spotify_auth' => Serialized::class
    ];

    /**
     * Retrieve the authenticated Spotify instance
     *
     * @return \App\Http\Integrations\Spotify\ApiConnector
     * @throws \ReflectionException
     * @throws \Saloon\Exceptions\InvalidResponseClassException
     * @throws \Saloon\Exceptions\OAuthConfigValidationException
     * @throws \Saloon\Exceptions\PendingRequestException
     */
    public function spotify(): ApiConnector
    {
        $auth = $this->spotify_auth;

        if ($auth->hasExpired()) {
            $auth = AuthConnector::make()->refreshAccessToken($auth);

            $this->spotify_auth = $auth;
            $this->save();
        }

        return ApiConnector::make()->authenticate($auth);
    }
}
