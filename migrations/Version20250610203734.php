<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250610203734 extends AbstractMigration {
		public function getDescription(): string {
			return 'The `kind` field will be used for part type moving forward';
		}

		public function up(Schema $schema): void {
			$this->addSql("ALTER TABLE monster_parts ADD kind VARCHAR(255) NOT NULL");
		}

		public function down(Schema $schema): void {
			$this->addSql("ALTER TABLE monster_parts DROP kind");
		}
	}
