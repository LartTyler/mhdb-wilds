<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250407065040 extends AbstractMigration {
		public function getDescription(): string {
			return 'Remove description from monster rewards';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE monster_reward_conditions DROP description');
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE monster_reward_conditions ADD description VARCHAR(255) DEFAULT NULL');
		}
	}
