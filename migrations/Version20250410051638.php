<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250410051638 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add row and column to weapon crafting data';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE weapon_crafting ADD `column` INT UNSIGNED NOT NULL, ADD row INT UNSIGNED NOT NULL');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE weapon_crafting DROP `column`, DROP row');
		}
	}
