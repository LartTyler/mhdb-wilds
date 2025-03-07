<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250307050752 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add type to decorations';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE decorations ADD kind VARCHAR(255) NOT NULL');
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE decorations DROP kind');
		}
	}
