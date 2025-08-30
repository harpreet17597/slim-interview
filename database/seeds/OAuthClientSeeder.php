<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

final class OAuthClientSeeder extends AbstractSeed
{
    public function run(): void
    {
        $table = $this->table('oauth_clients');

        // Data to insert/update
        $data = [
            'clientId'     => 'client',
            'secret' => 'secret123',
            'name'   => 'client',
        ];

        // Check if record exists
        $existing = $this->getAdapter()->fetchRow(
            "SELECT * FROM oauth_clients WHERE clientId = '{$data['clientId']}'"
        );


        if ($existing) {
            // Update existing record
            $this->execute("UPDATE oauth_clients SET secret = ?, name = ? WHERE clientId = ?", [
                $data['secret'],
                $data['name'],
                $data['clientId']
            ]);
        } else {
            // Insert new record
            $table->insert([$data])->saveData();
        }
    }
}
