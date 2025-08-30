<?php

namespace App\OAuth;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\PasswordGrant;
use App\OAuth\Repositories\ClientRepository;
use App\OAuth\Repositories\AccessTokenRepository;
use App\OAuth\Repositories\ScopeRepository;
use App\OAuth\Repositories\UserRepository;
use App\OAuth\Repositories\RefreshTokenRepository;
use DateInterval;

class OAuthServerFactory
{

    public static function create(): AuthorizationServer
    {
        $clientRepo       = new ClientRepository();
        $accessTokenRepo  = new AccessTokenRepository();
        $scopeRepo        = new ScopeRepository();
        $userRepo         = new UserRepository();
        $refreshTokenRepo = new RefreshTokenRepository();

        $privateKey    = new CryptKey(__DIR__ . '/../../storage/oauth/private.key', null, false);
        $encryptionKey = base64_encode(random_bytes(32)); // replace with a fixed key from env/config in production

        $server = new AuthorizationServer(
            $clientRepo,
            $accessTokenRepo,
            $scopeRepo,
            $privateKey,
            $encryptionKey
        );

        $passwordGrant = new PasswordGrant($userRepo, $refreshTokenRepo);
        $passwordGrant->setRefreshTokenTTL(new DateInterval('P1M')); // refresh token TTL

        $server->enableGrantType($passwordGrant, new DateInterval('PT1H')); // access token TTL 1h

        return $server;
    }
}
