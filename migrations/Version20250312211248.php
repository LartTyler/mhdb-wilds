<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250312211248 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add more data associated with hunting horns';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('CREATE TABLE hunting_horn_bubbles (id INT UNSIGNED AUTO_INCREMENT NOT NULL, kind VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, game_id INT NOT NULL, UNIQUE INDEX UNIQ_3CB07A633BC4BCD9 (kind), UNIQUE INDEX UNIQ_3CB07A63E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
			$this->addSql('CREATE TABLE hunting_horn_waves (id INT UNSIGNED AUTO_INCREMENT NOT NULL, kind VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, game_id INT NOT NULL, UNIQUE INDEX UNIQ_33365356E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
			$this->addSql('ALTER TABLE horn_melodies ADD game_id INT NOT NULL');
			$this->addSql('CREATE UNIQUE INDEX UNIQ_11393B94E48FD905 ON horn_melodies (game_id)');
			$this->addSql('ALTER TABLE horn_songs ADD effect_id INT UNSIGNED NOT NULL');
			$this->addSql('ALTER TABLE weapons DROP damage_kind');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			$this->addSql('DROP TABLE hunting_horn_bubbles');
			$this->addSql('DROP TABLE hunting_horn_waves');
			$this->addSql('ALTER TABLE horn_songs DROP effect_id');
			$this->addSql('DROP INDEX UNIQ_11393B94E48FD905 ON horn_melodies');
			$this->addSql('ALTER TABLE horn_melodies DROP game_id');
			$this->addSql('ALTER TABLE weapons ADD damage_kind VARCHAR(255) NOT NULL');
		}
	}
