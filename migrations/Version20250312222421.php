<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250312222421 extends AbstractMigration {
		public function getDescription(): string {
			return 'Allow database-level nulls for echo bubble and wave (for single table inheritance)';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE weapons CHANGE melody_id melody_id INT UNSIGNED DEFAULT NULL, CHANGE echo_bubble_id echo_bubble_id INT UNSIGNED DEFAULT NULL');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE weapons CHANGE melody_id melody_id INT UNSIGNED NOT NULL, CHANGE echo_bubble_id echo_bubble_id INT UNSIGNED NOT NULL');
			// @formatter:on
		}
	}
