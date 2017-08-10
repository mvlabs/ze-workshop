<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170412054906 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->createSequence('producers_id_seq');

        $producersTable = $schema->createTable('producers');

        $producersTable->addColumn('id', 'integer', ['default' => 'nextval(\'producers_id_seq\')', 'notnull' => true]);
        $producersTable->addColumn('name', 'string', ['length' => 1023, 'notnull' => true]);
        $producersTable->addColumn('street', 'string', ['length' => 1023]);
        $producersTable->addColumn('street_number', 'string', ['lenght' => 1023]);
        $producersTable->addColumn('zip_code', 'string', ['length' => 1023]);
        $producersTable->addColumn('city', 'string', ['length' => 1023]);
        $producersTable->addColumn('region', 'string', ['length' => 1023]);
        $producersTable->addColumn('country', 'string', ['length' => 2]);

        $producersTable->setPrimaryKey(['id']);
        $producersTable->addUniqueIndex(['name']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropSequence('producers_id_seq');
        $schema->dropTable('producers');
    }
}
