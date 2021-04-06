<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210406131624 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account ADD user_belongs_id INT NOT NULL');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A84D7E1E3 FOREIGN KEY (user_belongs_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_53A23E0A84D7E1E3 ON bank_account (user_belongs_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A84D7E1E3');
        $this->addSql('DROP INDEX IDX_53A23E0A84D7E1E3 ON bank_account');
        $this->addSql('ALTER TABLE bank_account DROP user_belongs_id');
    }
}
