<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250313024037 extends AbstractMigration {
		public function getDescription(): string {
			return 'Fix game ID index not including weapon kind';
		}

		public function up(Schema $schema): void {
			$this->addSql('DROP INDEX UNIQ_520EBBE1E48FD905 ON weapons');
			$this->addSql('CREATE UNIQUE INDEX UNIQ_520EBBE13BC4BCD9E48FD905 ON weapons (kind, game_id)');
		}

		public function down(Schema $schema): void {
			$this->addSql('DROP INDEX UNIQ_520EBBE13BC4BCD9E48FD905 ON weapons');
			$this->addSql('CREATE UNIQUE INDEX UNIQ_520EBBE1E48FD905 ON weapons (game_id)');
		}
	}
