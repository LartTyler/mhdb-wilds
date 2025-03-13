<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250313062733 extends AbstractMigration {
		public function getDescription(): string {
			return 'Remove unused tables, add phials to switch axe';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('DROP TABLE rapid_fire');
			$this->addSql('DROP TABLE auto_reload');
			$this->addSql('ALTER TABLE weapons ADD switch_axe_phial JSON DEFAULT NULL COMMENT \'(DC2Type:json_document)\'');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			// @formatter:off
			$this->addSql('CREATE TABLE rapid_fire (id INT UNSIGNED AUTO_INCREMENT NOT NULL, kind VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, level INT UNSIGNED NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
			$this->addSql('CREATE TABLE auto_reload (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ammo VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, level INT UNSIGNED NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
			$this->addSql('ALTER TABLE weapons DROP switch_axe_phial');
			// @formatter:on
		}
	}
