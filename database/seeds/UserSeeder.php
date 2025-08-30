<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

final class UserSeeder extends AbstractSeed
{
    public function run(): void
    {
        $users = [
            [
                'userId' => 1,
                'username' => 'root',
                'passwordHash' => password_hash('secret123', PASSWORD_BCRYPT),
            ],
            [
                'userId' => 2,
                'username' => 'john',
                'passwordHash' => password_hash('mypassword', PASSWORD_BCRYPT),
            ]
        ];

        foreach ($users as $user) {
            // Check if user exists
            $userId = (int)$user['userId'];
            $exists = $this->fetchRow("SELECT userId FROM users WHERE userId = $userId");
            if ($exists) {
                // Update existing record
                $this->execute(
                    "UPDATE users SET username = ?, passwordHash = ? WHERE userId = ?",
                    [$user['username'], $user['passwordHash'], $user['userId']]
                );
            } else {
                // Insert new record
                $this->table('users')->insert([$user])->saveData();
            }
        }
    }
}
