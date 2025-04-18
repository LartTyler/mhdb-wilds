<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250418031224 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add icon to skills and items';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE items ADD icon_id INT UNSIGNED NOT NULL, ADD icon_kind VARCHAR(255) NOT NULL, ADD icon_color_id INT UNSIGNED NOT NULL, ADD icon_color VARCHAR(255) NOT NULL');
			$this->addSql('ALTER TABLE skills ADD icon_id INT UNSIGNED NOT NULL, ADD icon_kind VARCHAR(255) NOT NULL');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE items DROP icon_id, DROP icon_kind, DROP icon_color_id, DROP icon_color');
			$this->addSql('ALTER TABLE skills DROP icon_id, DROP icon_kind');
		}
	}
