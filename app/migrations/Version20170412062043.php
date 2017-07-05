<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170412062043 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $historyTable = $schema->createTable('chocolates_history');

        $historyTable->addColumn('chocolate_id', 'string', ['length' => 1023, 'notnull' => true]);
        $historyTable->addColumn('status', 'string', ['length' => 1023, 'notnull' => true]);
        $historyTable->addColumn('user_id', 'string', ['length' => 36, 'notnull' => true]);
        $historyTable->addColumn('date_time', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'notnull' => true]);

        $historyTable->addForeignKeyConstraint('chocolates', ['chocolate_id'], ['id']);
        $historyTable->addForeignKeyConstraint('users', ['user_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('chocolates_history');
    }
}
