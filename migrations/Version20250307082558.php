<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250307082558 extends AbstractMigration {
		public function getDescription(): string {
			return 'Remove augmented field from armor defense';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE armors DROP defense_augmented');
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE armors ADD defense_augmented INT UNSIGNED NOT NULL');
		}
	}
