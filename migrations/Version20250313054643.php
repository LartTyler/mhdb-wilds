<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250313054643 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add special ammo to light bowguns';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE weapons ADD special_ammo VARCHAR(255) DEFAULT NULL');
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE weapons DROP special_ammo');
		}
	}
