<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250308065456 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add kind to skills';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE skills ADD kind VARCHAR(255) DEFAULT NULL');
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE skills DROP kind');
		}
	}
