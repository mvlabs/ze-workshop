<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170412061033 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $chocolatesTable = $schema->createTable('chocolates');

        $chocolatesTable->addColumn('id', 'string', ['fixed' => true, 'length' => 36, 'notnull' => true]);
        $chocolatesTable->addColumn('producer_id', 'integer', ['notnull' => true]);
        $chocolatesTable->addColumn('description', 'string', ['length' => 1023, 'notnull' => true]);
        $chocolatesTable->addColumn('cacao_percentage', 'integer');
        $chocolatesTable->addColumn('wrapper_type', 'string', ['notnull' => true]);
        $chocolatesTable->addColumn('quantity', 'integer');

        $chocolatesTable->setPrimaryKey(['id']);
        $chocolatesTable->addForeignKeyConstraint('producers', ['producer_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('chocolates');
    }
}
