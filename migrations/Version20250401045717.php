<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250401045717 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add new monster fields from merged data';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('CREATE TABLE monster_variants (id INT UNSIGNED AUTO_INCREMENT NOT NULL, monster_id INT UNSIGNED DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, kind VARCHAR(255) NOT NULL, INDEX IDX_55C1F6DC5FF1223 (monster_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
			$this->addSql('ALTER TABLE monster_variants ADD CONSTRAINT FK_55C1F6DC5FF1223 FOREIGN KEY (monster_id) REFERENCES monsters (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE monsters ADD features VARCHAR(255) DEFAULT NULL, ADD tips VARCHAR(255) DEFAULT NULL, ADD base_health INT UNSIGNED NOT NULL, ADD game_id INT NOT NULL, ADD size_base DOUBLE PRECISION NOT NULL, ADD size_mini DOUBLE PRECISION NOT NULL, ADD size_silver DOUBLE PRECISION NOT NULL, ADD size_gold DOUBLE PRECISION NOT NULL');
			$this->addSql('CREATE UNIQUE INDEX UNIQ_A1FAA7C8E48FD905 ON monsters (game_id)');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE monster_variants DROP FOREIGN KEY FK_55C1F6DC5FF1223');
			$this->addSql('DROP TABLE monster_variants');
			$this->addSql('DROP INDEX UNIQ_A1FAA7C8E48FD905 ON monsters');
			$this->addSql('ALTER TABLE monsters DROP features, DROP tips, DROP base_health, DROP game_id, DROP size_base, DROP size_mini, DROP size_silver, DROP size_gold');
			// @formatter:on
		}
	}
