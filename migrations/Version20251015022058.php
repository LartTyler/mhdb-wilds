<?php
	declare(strict_types=1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20251015022058 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add random flag to charms';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE charms ADD random TINYINT(1) NOT NULL');
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE charms DROP random');
		}
	}
