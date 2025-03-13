<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250313043345 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add kinsect level to insect glaives';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE weapons ADD kinsect_level INT UNSIGNED DEFAULT NULL');
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE weapons DROP kinsect_level');
		}
	}
