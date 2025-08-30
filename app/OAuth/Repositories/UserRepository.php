<?php

namespace App\OAuth\Repositories;

use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use App\Models\User;
use App\OAuth\Entities\UserEntity;

class UserRepository implements UserRepositoryInterface
{
    public function getUserEntityByUserCredentials($username, $password, $grantType, $clientEntity): ?UserEntityInterface
    {
        $user = User::where('username', $username)->first();
        if (!$user || !password_verify($password, $user->passwordHash)) {
            return null;
        }
        return new UserEntity($user->userId);
    }
}
