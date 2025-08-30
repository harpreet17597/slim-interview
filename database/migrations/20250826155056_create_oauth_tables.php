<?php

use Phinx\Migration\AbstractMigration;

final class CreateOauthTables extends AbstractMigration
{
    public function change(): void
    {
        // --- Clients ---
        $this->table('oauth_clients', ['id' => false, 'primary_key' => ['clientId']])
            ->addColumn('clientId', 'string', ['limit' => 100, 'null' => false])   // PK
            ->addColumn('secret', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('name', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('redirect_uri', 'string', ['limit' => 200, 'null' => true])
            ->create();

        // --- Access Tokens ---
        $this->table('oauth_access_tokens', ['id' => false, 'primary_key' => ['tokenId']])
            ->addColumn('tokenId', 'string', ['limit' => 100, 'null' => false])   // PK
            ->addColumn('userId', 'integer', ['null' => true])   // FK → users.userId
            ->addColumn('clientId', 'string', ['limit' => 100, 'null' => false])  // FK → oauth_clients.clientId
            ->addColumn('scopes', 'text', ['null' => true])
            ->addColumn('revoked', 'boolean', ['default' => false])
            ->addColumn('expires_at', 'datetime', ['null' => false])
            ->addForeignKey('userId', 'users', 'userId', ['delete' => 'CASCADE'])
            ->addForeignKey('clientId', 'oauth_clients', 'clientId', ['delete' => 'CASCADE'])
            ->create();

        // --- Refresh Tokens ---
        $this->table('oauth_refresh_tokens', ['id' => false, 'primary_key' => ['refreshTokenId']])
            ->addColumn('refreshTokenId', 'string', ['limit' => 100, 'null' => false])  // PK
            ->addColumn('accessTokenId', 'string', ['limit' => 100, 'null' => false])   // FK → oauth_access_tokens.tokenId
            ->addColumn('revoked', 'boolean', ['default' => false])
            ->addColumn('expires_at', 'datetime', ['null' => false])
            ->addForeignKey('accessTokenId', 'oauth_access_tokens', 'tokenId', ['delete' => 'CASCADE'])
            ->create();

        // --- Scopes (optional) ---
        $this->table('oauth_scopes', ['id' => false, 'primary_key' => ['scopeId']])
            ->addColumn('scopeId', 'string', ['limit' => 100, 'null' => false]) // PK
            ->addColumn('description', 'string', ['limit' => 255, 'null' => true])
            ->create();
    }
}
