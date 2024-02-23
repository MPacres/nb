<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Types\Types;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222073439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
       
        $table = $schema->createTable('users');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $table->addColumn('first_name', Types::STRING, ['length' => 255]);
        $table->addColumn('last_name', Types::STRING, ['length' => 255]);
        $table->addColumn('email', Types::STRING, ['length' => 255]);
    
        // Define primary key
        $table->setPrimaryKey(['id']);

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('users');
    }
}
