<?php

namespace App\OAuth\Repositories;

use Illuminate\Database\Capsule\Manager as DB;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use App\OAuth\Entities\ClientEntity;

class ClientRepository implements ClientRepositoryInterface
{
    public function getClientEntity($clientIdentifier): ?ClientEntityInterface
    {
        $row = DB::table('oauth_clients')->where('clientId', $clientIdentifier)->first();
        if (!$row) return null;

        return new ClientEntity($row->clientId);
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $row = DB::table('oauth_clients')->where('clientId', $clientIdentifier)->first();
        if (!$row) return false;

        return hash_equals($row->secret, (string)$clientSecret);
    }
}
