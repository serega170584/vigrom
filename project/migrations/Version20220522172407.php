<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220522172407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            INSERT INTO wallet(
               id,
               currency_id,
               name               
            )
            VALUES
            (1,'USD', 'Test wallet 1'),
            (3,'EUR', 'Test wallet 3'),
            (4,'UAH', 'Test wallet 4'),
            (5,'JPY', 'Test wallet 5')
        ");

        $this->addSql("
            INSERT INTO currency(
               id,
               rate               
            )
            VALUES
            ('USD', 75.11),
            ('EUR', 86.12),
            ('UAH', 2.11),
            ('JPY', 0.48)
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE wallet');
    }
}
