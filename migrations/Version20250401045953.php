<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250401045953 extends AbstractMigration {
		public function getDescription(): string {
			return 'Fix text column types';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE monsters CHANGE description description LONGTEXT DEFAULT NULL, CHANGE features features LONGTEXT DEFAULT NULL, CHANGE tips tips LONGTEXT DEFAULT NULL');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE monsters CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE features features VARCHAR(255) DEFAULT NULL, CHANGE tips tips VARCHAR(255) DEFAULT NULL');
			// @formatter:on
		}
	}
