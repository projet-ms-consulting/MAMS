<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327141527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expenses CHANGE expense_type expense_type VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE trips ADD trip_date DATE NOT NULL, CHANGE origin origin VARCHAR(100) NOT NULL, CHANGE destination destination VARCHAR(100) NOT NULL, CHANGE context context VARCHAR(100) NOT NULL, CHANGE category category VARCHAR(100) NOT NULL, CHANGE text description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user ADD last_name VARCHAR(255) NOT NULL, ADD first_name VARCHAR(255) NOT NULL, DROP lastname, DROP firstname, DROP is_verified');
        $this->addSql('ALTER TABLE vehicle ADD label VARCHAR(50) NOT NULL, CHANGE model model VARCHAR(50) NOT NULL, CHANGE plate plate VARCHAR(50) NOT NULL, CHANGE fiscal_horsepower fiscal_power INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trips DROP trip_date, CHANGE origin origin VARCHAR(255) NOT NULL, CHANGE destination destination VARCHAR(255) NOT NULL, CHANGE context context VARCHAR(255) NOT NULL, CHANGE category category VARCHAR(255) NOT NULL, CHANGE description text LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE expenses CHANGE expense_type expense_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE vehicle DROP label, CHANGE model model VARCHAR(255) NOT NULL, CHANGE plate plate VARCHAR(255) NOT NULL, CHANGE fiscal_power fiscal_horsepower INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD lastname VARCHAR(255) NOT NULL, ADD firstname VARCHAR(255) NOT NULL, ADD is_verified TINYINT(1) NOT NULL, DROP last_name, DROP first_name');
    }
}
