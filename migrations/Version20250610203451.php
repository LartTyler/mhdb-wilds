<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250610203451 extends AbstractMigration {
		public function getDescription(): string {
			return 'Allow part health to be null';
		}

		public function up(Schema $schema): void {
			$this->addSql("ALTER TABLE monster_parts CHANGE health health INT UNSIGNED DEFAULT NULL");
		}

		public function down(Schema $schema): void {
			$this->addSql("ALTER TABLE monster_parts CHANGE health health INT UNSIGNED NOT NULL");
		}
	}
