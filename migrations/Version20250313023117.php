<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250313023117 extends AbstractMigration {
		public function getDescription(): string {
			return 'Affinity can be negative';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE weapons CHANGE affinity affinity INT NOT NULL');
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE weapons CHANGE affinity affinity INT UNSIGNED NOT NULL');
		}
	}
