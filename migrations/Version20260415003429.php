<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260415003429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE armor_sets ADD set_bonus_skill_id INT UNSIGNED DEFAULT NULL, ADD group_bonus_skill_id INT UNSIGNED DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE armor_sets ADD CONSTRAINT FK_7C8A0B1098EC4E8B FOREIGN KEY (set_bonus_skill_id) REFERENCES skills (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE armor_sets ADD CONSTRAINT FK_7C8A0B10C3221BEA FOREIGN KEY (group_bonus_skill_id) REFERENCES skills (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7C8A0B1098EC4E8B ON armor_sets (set_bonus_skill_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7C8A0B10C3221BEA ON armor_sets (group_bonus_skill_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE skill_ranks ADD set_pieces_required SMALLINT DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE armor_sets DROP FOREIGN KEY FK_7C8A0B1098EC4E8B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE armor_sets DROP FOREIGN KEY FK_7C8A0B10C3221BEA
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_7C8A0B1098EC4E8B ON armor_sets
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_7C8A0B10C3221BEA ON armor_sets
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE armor_sets DROP set_bonus_skill_id, DROP group_bonus_skill_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE skill_ranks DROP set_pieces_required
        SQL);
    }
}
