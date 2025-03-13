<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250313044436 extends AbstractMigration {
		public function getDescription(): string {
			return 'Weapons reference skill ranks, not skills directly';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE weapon_skills DROP FOREIGN KEY FK_E8C439575585C142');
			$this->addSql('DROP INDEX IDX_E8C439575585C142 ON weapon_skills');
			$this->addSql('DROP INDEX `primary` ON weapon_skills');
			$this->addSql('ALTER TABLE weapon_skills CHANGE skill_id skill_rank_id INT UNSIGNED NOT NULL');
			$this->addSql('ALTER TABLE weapon_skills ADD CONSTRAINT FK_E8C439576CE3F9A6 FOREIGN KEY (skill_rank_id) REFERENCES skill_ranks (id) ON DELETE CASCADE');
			$this->addSql('CREATE INDEX IDX_E8C439576CE3F9A6 ON weapon_skills (skill_rank_id)');
			$this->addSql('ALTER TABLE weapon_skills ADD PRIMARY KEY (weapon_id, skill_rank_id)');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE weapon_skills DROP FOREIGN KEY FK_E8C439576CE3F9A6');
			$this->addSql('DROP INDEX IDX_E8C439576CE3F9A6 ON weapon_skills');
			$this->addSql('DROP INDEX `PRIMARY` ON weapon_skills');
			$this->addSql('ALTER TABLE weapon_skills CHANGE skill_rank_id skill_id INT UNSIGNED NOT NULL');
			$this->addSql('ALTER TABLE weapon_skills ADD CONSTRAINT FK_E8C439575585C142 FOREIGN KEY (skill_id) REFERENCES skills (id) ON DELETE CASCADE');
			$this->addSql('CREATE INDEX IDX_E8C439575585C142 ON weapon_skills (skill_id)');
			$this->addSql('ALTER TABLE weapon_skills ADD PRIMARY KEY (weapon_id, skill_id)');
			// @formatter:on
		}
	}
