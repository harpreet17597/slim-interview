<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('users', ['id' => false, 'primary_key' => 'userId'])
            ->addColumn('userId', 'integer', ['identity' => true]) // AUTO_INCREMENT
            ->addColumn('username', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('passwordHash', 'string', ['limit' => 255, 'null' => false])
            ->addIndex(['username'], ['unique' => true])
            ->create();
    }
}
