<?php

namespace App\OAuth\Repositories;

use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use App\OAuth\Entities\RefreshTokenEntity;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function getNewRefreshToken(): ?RefreshTokenEntityInterface
    {
        return new RefreshTokenEntity();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        // Save refresh token in DB
    }

    public function revokeRefreshToken($tokenId): void
    {
        // Mark token as revoked in DB
    }

    public function isRefreshTokenRevoked($tokenId): bool
    {
        // Check if token is revoked
        return false;
    }
}
