<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250401025327 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add missing columns to camps';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE camps ADD floor INT UNSIGNED NOT NULL, ADD risk VARCHAR(255) NOT NULL, ADD position_x DOUBLE PRECISION NOT NULL, ADD position_y DOUBLE PRECISION NOT NULL, ADD position_z DOUBLE PRECISION NOT NULL');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE camps DROP floor, DROP risk, DROP position_x, DROP position_y, DROP position_z');
		}
	}
