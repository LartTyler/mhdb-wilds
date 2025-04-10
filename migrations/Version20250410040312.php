<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250410040312 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add weapon series entity';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('CREATE TABLE weapon_series (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, game_id INT NOT NULL, UNIQUE INDEX UNIQ_7E52E0AE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
			$this->addSql('ALTER TABLE weapons ADD series_id INT UNSIGNED DEFAULT NULL');
			$this->addSql('ALTER TABLE weapons ADD CONSTRAINT FK_520EBBE15278319C FOREIGN KEY (series_id) REFERENCES weapon_series (id) ON DELETE CASCADE');
			$this->addSql('CREATE INDEX IDX_520EBBE15278319C ON weapons (series_id)');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE weapons DROP FOREIGN KEY FK_520EBBE15278319C');
			$this->addSql('DROP TABLE weapon_series');
			$this->addSql('DROP INDEX IDX_520EBBE15278319C ON weapons');
			$this->addSql('ALTER TABLE weapons DROP series_id');
		}
	}
