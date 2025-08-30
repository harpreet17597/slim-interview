<?php

namespace App\OAuth\Entities;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ClientEntity implements ClientEntityInterface
{
    use EntityTrait, ClientTrait;

    public function __construct(string $identifier = '')
    {
        $this->setIdentifier($identifier);
    }

    public function setName(string $name): void
    {
        $this->name = $name; // from ClientTrait
    }

    public function setRedirectUri($uri): void
    {
        $this->redirectUri = $uri; // from ClientTrait
    }

    public function setConfidential(bool $isConfidential): void
    {
        $this->isConfidential = $isConfidential; // from ClientTrait
    }
}
