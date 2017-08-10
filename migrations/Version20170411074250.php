<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170411074250 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $userTable = $schema->createTable('users');

        $userTable->addColumn('id', 'string', ['fixed' => true, 'length' => 36, 'notnull' => true]);
        $userTable->addColumn('username', 'string', ['length' => 1023, 'notnull' => true]);
        $userTable->addColumn('password', 'string', ['length' => 1023, 'notnull' => true]);
        $userTable->addColumn('admin', 'boolean');

        $userTable->setPrimaryKey(['id']);
        $userTable->addUniqueIndex(['username']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('users');
    }
}
