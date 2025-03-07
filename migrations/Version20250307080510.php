<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250307080510 extends AbstractMigration {
		public function getDescription(): string {
			return 'Improve armor and armor set fields';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE armor_set_bonuses ADD skill_id INT UNSIGNED NOT NULL, DROP name');
			$this->addSql('ALTER TABLE armor_set_bonuses ADD CONSTRAINT FK_FC4A63D15585C142 FOREIGN KEY (skill_id) REFERENCES skills (id) ON DELETE CASCADE');
			$this->addSql('CREATE INDEX IDX_FC4A63D15585C142 ON armor_set_bonuses (skill_id)');
			$this->addSql('ALTER TABLE armor_sets ADD group_bonus_id INT UNSIGNED DEFAULT NULL, ADD game_id INT NOT NULL');
			$this->addSql('ALTER TABLE armor_sets ADD CONSTRAINT FK_7C8A0B1060641A19 FOREIGN KEY (group_bonus_id) REFERENCES armor_set_bonuses (id) ON DELETE SET NULL');
			$this->addSql('CREATE UNIQUE INDEX UNIQ_7C8A0B10E48FD905 ON armor_sets (game_id)');
			$this->addSql('CREATE INDEX IDX_7C8A0B1060641A19 ON armor_sets (group_bonus_id)');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE armor_set_bonuses DROP FOREIGN KEY FK_FC4A63D15585C142');
			$this->addSql('DROP INDEX IDX_FC4A63D15585C142 ON armor_set_bonuses');
			$this->addSql('ALTER TABLE armor_set_bonuses ADD name VARCHAR(255) DEFAULT NULL, DROP skill_id');
			$this->addSql('ALTER TABLE armor_sets DROP FOREIGN KEY FK_7C8A0B1060641A19');
			$this->addSql('DROP INDEX UNIQ_7C8A0B10E48FD905 ON armor_sets');
			$this->addSql('DROP INDEX IDX_7C8A0B1060641A19 ON armor_sets');
			$this->addSql('ALTER TABLE armor_sets DROP group_bonus_id, DROP game_id');
		}
	}
