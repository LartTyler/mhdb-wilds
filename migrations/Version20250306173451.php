<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250306173451 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add game ID to items';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE items ADD game_id INT NOT NULL');
			$this->addSql('CREATE UNIQUE INDEX UNIQ_E11EE94DE48FD905 ON items (game_id)');
		}

		public function down(Schema $schema): void {
			$this->addSql('DROP INDEX UNIQ_E11EE94DE48FD905 ON items');
			$this->addSql('ALTER TABLE items DROP game_id');
		}
	}
