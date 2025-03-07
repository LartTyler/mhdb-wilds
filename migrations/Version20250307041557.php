<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250307041557 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add description and game ID to decorations';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE decorations ADD description VARCHAR(255) DEFAULT NULL, ADD value INT UNSIGNED NOT NULL, ADD game_id INT NOT NULL');
			$this->addSql('CREATE UNIQUE INDEX UNIQ_53BB9DDDE48FD905 ON decorations (game_id)');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			$this->addSql('DROP INDEX UNIQ_53BB9DDDE48FD905 ON decorations');
			$this->addSql('ALTER TABLE decorations DROP description, DROP value, DROP game_id');
		}
	}
