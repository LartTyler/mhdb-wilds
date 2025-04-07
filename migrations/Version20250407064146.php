<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250407064146 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add monster parts and update some existing tables';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('CREATE TABLE monster_breakable_parts (id INT UNSIGNED AUTO_INCREMENT NOT NULL, monster_id INT UNSIGNED NOT NULL, part VARCHAR(255) NOT NULL, name LONGTEXT DEFAULT NULL, INDEX IDX_B6D437FCC5FF1223 (monster_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
			$this->addSql('ALTER TABLE monster_breakable_parts ADD CONSTRAINT FK_B6D437FCC5FF1223 FOREIGN KEY (monster_id) REFERENCES monsters (id)');
			$this->addSql('ALTER TABLE monster_resistances ADD effect VARCHAR(255) DEFAULT NULL');
			$this->addSql('ALTER TABLE monster_reward_conditions ADD part VARCHAR(255) DEFAULT NULL, CHANGE subtype description VARCHAR(255) DEFAULT NULL');
			$this->addSql('ALTER TABLE monster_weaknesses ADD effect VARCHAR(255) DEFAULT NULL');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE monster_breakable_parts DROP FOREIGN KEY FK_B6D437FCC5FF1223');
			$this->addSql('DROP TABLE monster_breakable_parts');
			$this->addSql('ALTER TABLE monster_weaknesses DROP effect');
			$this->addSql('ALTER TABLE monster_reward_conditions ADD subtype VARCHAR(255) DEFAULT NULL, DROP description, DROP part');
			$this->addSql('ALTER TABLE monster_resistances DROP effect');
			// @formatter:on
		}
	}
