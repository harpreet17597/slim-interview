<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBorrowLogTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('borrowlog', ['id' => false, 'primary_key' => 'borrowLogId'])
            ->addColumn('borrowLogId', 'integer', ['identity' => true])
            ->addColumn('bookId', 'integer', ['null' => false])
            ->addColumn('userId', 'integer', ['null' => false])
            ->addColumn('borrowLogDateTime', 'datetime', ['null' => false])
            ->addForeignKey('bookId', 'books', 'bookId', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('userId', 'users', 'userId', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
