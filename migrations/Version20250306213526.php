<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250306213526 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add game ID to skills';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE skills ADD game_id INT NOT NULL');
			$this->addSql('CREATE UNIQUE INDEX UNIQ_D5311670E48FD905 ON skills (game_id)');
		}

		public function down(Schema $schema): void {
			$this->addSql('DROP INDEX UNIQ_D5311670E48FD905 ON skills');
			$this->addSql('ALTER TABLE skills DROP game_id');
		}
	}
