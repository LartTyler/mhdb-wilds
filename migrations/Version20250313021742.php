<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250313021742 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add description to weapons';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE weapons ADD description VARCHAR(255) DEFAULT NULL');
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE weapons DROP description');
		}
	}
