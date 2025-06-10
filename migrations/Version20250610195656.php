<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250610195656 extends AbstractMigration {
		public function getDescription(): string {
			return 'Replace reward-only breakable parts list with full parts list';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql("CREATE TABLE monster_parts (id INT UNSIGNED AUTO_INCREMENT NOT NULL, monster_id INT UNSIGNED NOT NULL, part VARCHAR(255) NOT NULL, name LONGTEXT DEFAULT NULL, health INT UNSIGNED NOT NULL, kinsect_essence VARCHAR(255) DEFAULT NULL, multipliers_slash DOUBLE PRECISION NOT NULL, multipliers_blunt DOUBLE PRECISION NOT NULL, multipliers_pierce DOUBLE PRECISION NOT NULL, multipliers_fire DOUBLE PRECISION NOT NULL, multipliers_water DOUBLE PRECISION NOT NULL, multipliers_thunder DOUBLE PRECISION NOT NULL, multipliers_ice DOUBLE PRECISION NOT NULL, multipliers_dragon DOUBLE PRECISION NOT NULL, multipliers_stun DOUBLE PRECISION NOT NULL, INDEX IDX_1FB89BBBC5FF1223 (monster_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB");
			$this->addSql("CREATE TABLE deprecated_monster_breakable_parts (monster_id INT UNSIGNED NOT NULL, monster_part_id INT UNSIGNED NOT NULL, INDEX IDX_B131B4DDC5FF1223 (monster_id), UNIQUE INDEX UNIQ_B131B4DDEE82FAD3 (monster_part_id), PRIMARY KEY(monster_id, monster_part_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB");
			$this->addSql("ALTER TABLE monster_parts ADD CONSTRAINT FK_1FB89BBBC5FF1223 FOREIGN KEY (monster_id) REFERENCES monsters (id)");
			$this->addSql("ALTER TABLE deprecated_monster_breakable_parts ADD CONSTRAINT FK_B131B4DDC5FF1223 FOREIGN KEY (monster_id) REFERENCES monsters (id) ON DELETE CASCADE");
			$this->addSql("ALTER TABLE deprecated_monster_breakable_parts ADD CONSTRAINT FK_B131B4DDEE82FAD3 FOREIGN KEY (monster_part_id) REFERENCES monster_parts (id)");
			$this->addSql("ALTER TABLE monster_breakable_parts DROP FOREIGN KEY FK_B6D437FCC5FF1223");
			$this->addSql("DROP TABLE monster_breakable_parts");
			// @formatter:on
		}

		public function down(Schema $schema): void {
			// @formatter:off
			$this->addSql("CREATE TABLE monster_breakable_parts (id INT UNSIGNED AUTO_INCREMENT NOT NULL, monster_id INT UNSIGNED NOT NULL, part VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, name LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_B6D437FCC5FF1223 (monster_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = '' ");
			$this->addSql("ALTER TABLE monster_breakable_parts ADD CONSTRAINT FK_B6D437FCC5FF1223 FOREIGN KEY (monster_id) REFERENCES monsters (id)");
			$this->addSql("ALTER TABLE monster_parts DROP FOREIGN KEY FK_1FB89BBBC5FF1223");
			$this->addSql("ALTER TABLE deprecated_monster_breakable_parts DROP FOREIGN KEY FK_B131B4DDC5FF1223");
			$this->addSql("ALTER TABLE deprecated_monster_breakable_parts DROP FOREIGN KEY FK_B131B4DDEE82FAD3");
			$this->addSql("DROP TABLE monster_parts");
			$this->addSql("DROP TABLE deprecated_monster_breakable_parts");
			// @formatter:on
		}
	}
