<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250312222205 extends AbstractMigration {
		public function getDescription(): string {
			return 'Fix melody<>song ownership, add echo bubble and wave to horn';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('DROP INDEX `primary` ON horn_melody_songs');
			$this->addSql('ALTER TABLE horn_melody_songs ADD PRIMARY KEY (horn_melody_id, horn_song_id)');
			$this->addSql('ALTER TABLE weapons ADD echo_bubble_id INT UNSIGNED NOT NULL, ADD echo_wave_id INT UNSIGNED DEFAULT NULL, CHANGE melody_id melody_id INT UNSIGNED NOT NULL');
			$this->addSql('ALTER TABLE weapons ADD CONSTRAINT FK_520EBBE1C99BCFCA FOREIGN KEY (echo_bubble_id) REFERENCES hunting_horn_bubbles (id)');
			$this->addSql('ALTER TABLE weapons ADD CONSTRAINT FK_520EBBE1C6F3FAF3 FOREIGN KEY (echo_wave_id) REFERENCES hunting_horn_waves (id)');
			$this->addSql('CREATE INDEX IDX_520EBBE1C99BCFCA ON weapons (echo_bubble_id)');
			$this->addSql('CREATE INDEX IDX_520EBBE1C6F3FAF3 ON weapons (echo_wave_id)');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			// @formatter:off
			$this->addSql('DROP INDEX `PRIMARY` ON horn_melody_songs');
			$this->addSql('ALTER TABLE horn_melody_songs ADD PRIMARY KEY (horn_song_id, horn_melody_id)');
			$this->addSql('ALTER TABLE weapons DROP FOREIGN KEY FK_520EBBE1C99BCFCA');
			$this->addSql('ALTER TABLE weapons DROP FOREIGN KEY FK_520EBBE1C6F3FAF3');
			$this->addSql('DROP INDEX IDX_520EBBE1C99BCFCA ON weapons');
			$this->addSql('DROP INDEX IDX_520EBBE1C6F3FAF3 ON weapons');
			$this->addSql('ALTER TABLE weapons DROP echo_bubble_id, DROP echo_wave_id, CHANGE melody_id melody_id INT UNSIGNED DEFAULT NULL');
			// @formatter:on
		}
	}
