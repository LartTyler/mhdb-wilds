<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250307080847 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add description to armor';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE armors ADD description LONGTEXT DEFAULT NULL');
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE armors DROP description');
		}
	}
