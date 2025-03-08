<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250308075453 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add new columns to charms and charm ranks';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE charm_ranks ADD description LONGTEXT DEFAULT NULL');
			$this->addSql('ALTER TABLE charms ADD game_id INT NOT NULL, DROP name');
			$this->addSql('CREATE UNIQUE INDEX UNIQ_5B50F9EFE48FD905 ON charms (game_id)');
			$this->addSql('ALTER TABLE crafting_material_costs DROP FOREIGN KEY FK_1B8FBFE6126F525E');
			$this->addSql('ALTER TABLE crafting_material_costs CHANGE item_id item_id INT UNSIGNED NOT NULL');
			$this->addSql('ALTER TABLE crafting_material_costs ADD CONSTRAINT FK_1B8FBFE6126F525E FOREIGN KEY (item_id) REFERENCES items (id) ON DELETE CASCADE');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			// @formatter:off
			$this->addSql('ALTER TABLE crafting_material_costs DROP FOREIGN KEY FK_1B8FBFE6126F525E');
			$this->addSql('ALTER TABLE crafting_material_costs CHANGE item_id item_id INT UNSIGNED DEFAULT NULL');
			$this->addSql('ALTER TABLE crafting_material_costs ADD CONSTRAINT FK_1B8FBFE6126F525E FOREIGN KEY (item_id) REFERENCES items (id)');
			$this->addSql('DROP INDEX UNIQ_5B50F9EFE48FD905 ON charms');
			$this->addSql('ALTER TABLE charms ADD name VARCHAR(255) DEFAULT NULL, DROP game_id');
			$this->addSql('ALTER TABLE charm_ranks DROP description');
			// @formatter:on
		}
	}
