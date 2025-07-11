<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250711013026 extends AbstractMigration {
		public function getDescription(): string {
			return 'Remove deprecated join table';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql("ALTER TABLE deprecated_monster_breakable_parts DROP FOREIGN KEY FK_B131B4DDC5FF1223");
			$this->addSql("ALTER TABLE deprecated_monster_breakable_parts DROP FOREIGN KEY FK_B131B4DDEE82FAD3");
			$this->addSql("DROP TABLE deprecated_monster_breakable_parts");
			// @formatter:on
		}

		public function down(Schema $schema): void {
			// @formatter:off
			$this->addSql("CREATE TABLE deprecated_monster_breakable_parts (monster_id INT UNSIGNED NOT NULL, monster_part_id INT UNSIGNED NOT NULL, INDEX IDX_B131B4DDC5FF1223 (monster_id), UNIQUE INDEX UNIQ_B131B4DDEE82FAD3 (monster_part_id), PRIMARY KEY(monster_id, monster_part_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = ''");
			$this->addSql("ALTER TABLE deprecated_monster_breakable_parts ADD CONSTRAINT FK_B131B4DDC5FF1223 FOREIGN KEY (monster_id) REFERENCES monsters (id) ON DELETE CASCADE");
			$this->addSql("ALTER TABLE deprecated_monster_breakable_parts ADD CONSTRAINT FK_B131B4DDEE82FAD3 FOREIGN KEY (monster_part_id) REFERENCES monster_parts (id)");
			// @formatter:on
		}
	}
