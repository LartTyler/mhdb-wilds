<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250409221725 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add name field to skill ranks';
		}

		public function up(Schema $schema): void {
			$this->addSql('ALTER TABLE skill_ranks ADD name LONGTEXT DEFAULT NULL');
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE skill_ranks DROP name');
		}
	}
