<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBooksTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('books', ['id' => false, 'primary_key' => 'bookId'])
            ->addColumn('bookId', 'integer', ['identity' => true])
            ->addColumn('bookTitle', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('bookAuthor', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('bookPublishYear', 'integer', ['null' => true])
            ->addIndex(['bookTitle', 'bookAuthor'], ['type' => 'fulltext'])
            ->create();
    }
}
