<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250401021834 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add game ID to camps and locations';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE camps ADD game_id INT NOT NULL');
			$this->addSql('CREATE UNIQUE INDEX UNIQ_3D166BE5E48FD905 ON camps (game_id)');
			$this->addSql('ALTER TABLE locations ADD game_id INT NOT NULL');
			$this->addSql('CREATE UNIQUE INDEX UNIQ_17E64ABAE48FD905 ON locations (game_id)');
		}

		public function down(Schema $schema): void {
			$this->addSql('DROP INDEX UNIQ_17E64ABAE48FD905 ON locations');
			$this->addSql('ALTER TABLE locations DROP game_id');
			$this->addSql('DROP INDEX UNIQ_3D166BE5E48FD905 ON camps');
			$this->addSql('ALTER TABLE camps DROP game_id');
		}
	}
