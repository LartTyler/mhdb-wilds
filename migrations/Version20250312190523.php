<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250312190523 extends AbstractMigration {
		public function getDescription(): string {
			return 'Major restructure following data research';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE heavy_bowgun_ammo DROP FOREIGN KEY FK_1B7A73659F20CC20');
			$this->addSql('ALTER TABLE light_bowgun_ammo DROP FOREIGN KEY FK_7AF5A029F20CC20');
			$this->addSql('CREATE TABLE horn_melody_songs (horn_song_id INT UNSIGNED NOT NULL, horn_melody_id INT UNSIGNED NOT NULL, INDEX IDX_5FEF6A4A416D3D13 (horn_song_id), INDEX IDX_5FEF6A4AA666E9A8 (horn_melody_id), PRIMARY KEY(horn_song_id, horn_melody_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
			$this->addSql('ALTER TABLE horn_melody_songs ADD CONSTRAINT FK_5FEF6A4A416D3D13 FOREIGN KEY (horn_song_id) REFERENCES horn_songs (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE horn_melody_songs ADD CONSTRAINT FK_5FEF6A4AA666E9A8 FOREIGN KEY (horn_melody_id) REFERENCES horn_melodies (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE light_bowgun_auto_reload DROP FOREIGN KEY FK_3ABB16A69D029C12');
			$this->addSql('ALTER TABLE light_bowgun_auto_reload DROP FOREIGN KEY FK_3ABB16A6B13121A6');
			$this->addSql('ALTER TABLE sharpness DROP FOREIGN KEY FK_E62BD8AC95B82273');
			$this->addSql('ALTER TABLE light_bowgun_rapid_fire DROP FOREIGN KEY FK_6091D3AE9D029C12');
			$this->addSql('ALTER TABLE light_bowgun_rapid_fire DROP FOREIGN KEY FK_6091D3AEE9056C50');
			$this->addSql('ALTER TABLE heavy_bowgun_auto_reload DROP FOREIGN KEY FK_FD3283DB3D3C464');
			$this->addSql('ALTER TABLE heavy_bowgun_auto_reload DROP FOREIGN KEY FK_FD3283DB13121A6');
			$this->addSql('DROP TABLE light_bowgun_auto_reload');
			$this->addSql('DROP TABLE sharpness');
			$this->addSql('DROP TABLE light_bowgun_rapid_fire');
			$this->addSql('DROP TABLE heavy_bowgun_auto_reload');
			$this->addSql('DROP TABLE weapon_ammo');
			$this->addSql('ALTER TABLE heavy_bowgun_ammo DROP FOREIGN KEY FK_1B7A7365B3D3C464');
			$this->addSql('DROP INDEX IDX_1B7A73659F20CC20 ON heavy_bowgun_ammo');
			$this->addSql('DROP INDEX IDX_1B7A7365B3D3C464 ON heavy_bowgun_ammo');
			$this->addSql('ALTER TABLE heavy_bowgun_ammo ADD id INT UNSIGNED AUTO_INCREMENT NOT NULL, ADD weapon_id INT UNSIGNED NOT NULL, ADD kind VARCHAR(255) NOT NULL, ADD level INT UNSIGNED NOT NULL, ADD capacity INT UNSIGNED NOT NULL, DROP heavy_bowgun_id, DROP ammo_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
			$this->addSql('ALTER TABLE heavy_bowgun_ammo ADD CONSTRAINT FK_1B7A736595B82273 FOREIGN KEY (weapon_id) REFERENCES weapons (id) ON DELETE CASCADE');
			$this->addSql('CREATE INDEX IDX_1B7A736595B82273 ON heavy_bowgun_ammo (weapon_id)');
			$this->addSql('ALTER TABLE horn_songs DROP FOREIGN KEY FK_168A3F76851F6525');
			$this->addSql('DROP INDEX IDX_168A3F76851F6525 ON horn_songs');
			$this->addSql('ALTER TABLE horn_songs DROP melody_id, DROP duration, DROP personal, CHANGE effects name VARCHAR(255) DEFAULT NULL');
			$this->addSql('ALTER TABLE light_bowgun_ammo DROP FOREIGN KEY FK_7AF5A029D029C12');
			$this->addSql('DROP INDEX IDX_7AF5A029D029C12 ON light_bowgun_ammo');
			$this->addSql('DROP INDEX IDX_7AF5A029F20CC20 ON light_bowgun_ammo');
			$this->addSql('ALTER TABLE light_bowgun_ammo ADD id INT UNSIGNED AUTO_INCREMENT NOT NULL, ADD weapon_id INT UNSIGNED NOT NULL, ADD kind VARCHAR(255) NOT NULL, ADD level INT UNSIGNED NOT NULL, ADD capacity INT UNSIGNED NOT NULL, ADD rapid TINYINT(1) NOT NULL, DROP light_bowgun_id, DROP ammo_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
			$this->addSql('ALTER TABLE light_bowgun_ammo ADD CONSTRAINT FK_7AF5A0295B82273 FOREIGN KEY (weapon_id) REFERENCES weapons (id) ON DELETE CASCADE');
			$this->addSql('CREATE INDEX IDX_7AF5A0295B82273 ON light_bowgun_ammo (weapon_id)');
			$this->addSql('ALTER TABLE weapons ADD game_id INT NOT NULL, ADD handicraft JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD sharpness_red INT UNSIGNED DEFAULT NULL, ADD sharpness_orange INT UNSIGNED DEFAULT NULL, ADD sharpness_yellow INT UNSIGNED DEFAULT NULL, ADD sharpness_green INT UNSIGNED DEFAULT NULL, ADD sharpness_blue INT UNSIGNED DEFAULT NULL, ADD sharpness_white INT UNSIGNED DEFAULT NULL, ADD sharpness_purple INT UNSIGNED DEFAULT NULL, ADD phial VARCHAR(255) DEFAULT NULL, DROP deviation, DROP special_ammo');
			$this->addSql('CREATE UNIQUE INDEX UNIQ_520EBBE1E48FD905 ON weapons (game_id)');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			// @formatter:off
			$this->addSql('CREATE TABLE light_bowgun_auto_reload (light_bowgun_id INT UNSIGNED NOT NULL, auto_reload_id INT UNSIGNED NOT NULL, INDEX IDX_3ABB16A6B13121A6 (auto_reload_id), INDEX IDX_3ABB16A69D029C12 (light_bowgun_id), PRIMARY KEY(light_bowgun_id, auto_reload_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
			$this->addSql('CREATE TABLE sharpness (id INT UNSIGNED AUTO_INCREMENT NOT NULL, weapon_id INT UNSIGNED NOT NULL, red INT UNSIGNED NOT NULL, orange INT UNSIGNED NOT NULL, yellow INT UNSIGNED NOT NULL, green INT UNSIGNED NOT NULL, blue INT UNSIGNED NOT NULL, white INT UNSIGNED NOT NULL, purple INT UNSIGNED NOT NULL, INDEX IDX_E62BD8AC95B82273 (weapon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
			$this->addSql('CREATE TABLE light_bowgun_rapid_fire (light_bowgun_id INT UNSIGNED NOT NULL, rapid_fire_id INT UNSIGNED NOT NULL, INDEX IDX_6091D3AE9D029C12 (light_bowgun_id), INDEX IDX_6091D3AEE9056C50 (rapid_fire_id), PRIMARY KEY(light_bowgun_id, rapid_fire_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
			$this->addSql('CREATE TABLE heavy_bowgun_auto_reload (heavy_bowgun_id INT UNSIGNED NOT NULL, auto_reload_id INT UNSIGNED NOT NULL, INDEX IDX_FD3283DB13121A6 (auto_reload_id), INDEX IDX_FD3283DB3D3C464 (heavy_bowgun_id), PRIMARY KEY(heavy_bowgun_id, auto_reload_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
			$this->addSql('CREATE TABLE weapon_ammo (id INT UNSIGNED AUTO_INCREMENT NOT NULL, kind VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, capacities JSON NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
			$this->addSql('ALTER TABLE light_bowgun_auto_reload ADD CONSTRAINT FK_3ABB16A69D029C12 FOREIGN KEY (light_bowgun_id) REFERENCES weapons (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE light_bowgun_auto_reload ADD CONSTRAINT FK_3ABB16A6B13121A6 FOREIGN KEY (auto_reload_id) REFERENCES auto_reload (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE sharpness ADD CONSTRAINT FK_E62BD8AC95B82273 FOREIGN KEY (weapon_id) REFERENCES weapons (id)');
			$this->addSql('ALTER TABLE light_bowgun_rapid_fire ADD CONSTRAINT FK_6091D3AE9D029C12 FOREIGN KEY (light_bowgun_id) REFERENCES weapons (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE light_bowgun_rapid_fire ADD CONSTRAINT FK_6091D3AEE9056C50 FOREIGN KEY (rapid_fire_id) REFERENCES rapid_fire (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE heavy_bowgun_auto_reload ADD CONSTRAINT FK_FD3283DB3D3C464 FOREIGN KEY (heavy_bowgun_id) REFERENCES weapons (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE heavy_bowgun_auto_reload ADD CONSTRAINT FK_FD3283DB13121A6 FOREIGN KEY (auto_reload_id) REFERENCES auto_reload (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE horn_melody_songs DROP FOREIGN KEY FK_5FEF6A4A416D3D13');
			$this->addSql('ALTER TABLE horn_melody_songs DROP FOREIGN KEY FK_5FEF6A4AA666E9A8');
			$this->addSql('DROP TABLE horn_melody_songs');
			$this->addSql('ALTER TABLE horn_songs ADD melody_id INT UNSIGNED NOT NULL, ADD duration INT UNSIGNED NOT NULL, ADD personal TINYINT(1) NOT NULL, CHANGE name effects VARCHAR(255) DEFAULT NULL');
			$this->addSql('ALTER TABLE horn_songs ADD CONSTRAINT FK_168A3F76851F6525 FOREIGN KEY (melody_id) REFERENCES horn_melodies (id)');
			$this->addSql('CREATE INDEX IDX_168A3F76851F6525 ON horn_songs (melody_id)');
			$this->addSql('ALTER TABLE heavy_bowgun_ammo MODIFY id INT UNSIGNED NOT NULL');
			$this->addSql('ALTER TABLE heavy_bowgun_ammo DROP FOREIGN KEY FK_1B7A736595B82273');
			$this->addSql('DROP INDEX IDX_1B7A736595B82273 ON heavy_bowgun_ammo');
			$this->addSql('DROP INDEX `PRIMARY` ON heavy_bowgun_ammo');
			$this->addSql('ALTER TABLE heavy_bowgun_ammo ADD heavy_bowgun_id INT UNSIGNED NOT NULL, ADD ammo_id INT UNSIGNED NOT NULL, DROP id, DROP weapon_id, DROP kind, DROP level, DROP capacity');
			$this->addSql('ALTER TABLE heavy_bowgun_ammo ADD CONSTRAINT FK_1B7A7365B3D3C464 FOREIGN KEY (heavy_bowgun_id) REFERENCES weapons (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE heavy_bowgun_ammo ADD CONSTRAINT FK_1B7A73659F20CC20 FOREIGN KEY (ammo_id) REFERENCES weapon_ammo (id) ON DELETE CASCADE');
			$this->addSql('CREATE INDEX IDX_1B7A73659F20CC20 ON heavy_bowgun_ammo (ammo_id)');
			$this->addSql('CREATE INDEX IDX_1B7A7365B3D3C464 ON heavy_bowgun_ammo (heavy_bowgun_id)');
			$this->addSql('ALTER TABLE heavy_bowgun_ammo ADD PRIMARY KEY (heavy_bowgun_id, ammo_id)');
			$this->addSql('ALTER TABLE light_bowgun_ammo MODIFY id INT UNSIGNED NOT NULL');
			$this->addSql('ALTER TABLE light_bowgun_ammo DROP FOREIGN KEY FK_7AF5A0295B82273');
			$this->addSql('DROP INDEX IDX_7AF5A0295B82273 ON light_bowgun_ammo');
			$this->addSql('DROP INDEX `PRIMARY` ON light_bowgun_ammo');
			$this->addSql('ALTER TABLE light_bowgun_ammo ADD light_bowgun_id INT UNSIGNED NOT NULL, ADD ammo_id INT UNSIGNED NOT NULL, DROP id, DROP weapon_id, DROP kind, DROP level, DROP capacity, DROP rapid');
			$this->addSql('ALTER TABLE light_bowgun_ammo ADD CONSTRAINT FK_7AF5A029D029C12 FOREIGN KEY (light_bowgun_id) REFERENCES weapons (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE light_bowgun_ammo ADD CONSTRAINT FK_7AF5A029F20CC20 FOREIGN KEY (ammo_id) REFERENCES weapon_ammo (id) ON DELETE CASCADE');
			$this->addSql('CREATE INDEX IDX_7AF5A029D029C12 ON light_bowgun_ammo (light_bowgun_id)');
			$this->addSql('CREATE INDEX IDX_7AF5A029F20CC20 ON light_bowgun_ammo (ammo_id)');
			$this->addSql('ALTER TABLE light_bowgun_ammo ADD PRIMARY KEY (light_bowgun_id, ammo_id)');
			$this->addSql('DROP INDEX UNIQ_520EBBE1E48FD905 ON weapons');
			$this->addSql('ALTER TABLE weapons ADD special_ammo VARCHAR(255) DEFAULT NULL, DROP game_id, DROP handicraft, DROP sharpness_red, DROP sharpness_orange, DROP sharpness_yellow, DROP sharpness_green, DROP sharpness_blue, DROP sharpness_white, DROP sharpness_purple, CHANGE phial deviation VARCHAR(255) DEFAULT NULL');
			// @formatter:on
		}
	}
