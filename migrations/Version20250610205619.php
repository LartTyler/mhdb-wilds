<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250610205619 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add icon color info to decorations';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql("ALTER TABLE decorations ADD icon_color VARCHAR(255) NOT NULL, ADD icon_color_id INT UNSIGNED NOT NULL");
			// @formatter:on
		}

		public function down(Schema $schema): void {
			$this->addSql("ALTER TABLE decorations DROP icon_color, DROP icon_color_id");
		}
	}
