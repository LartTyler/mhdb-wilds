<?php
	declare(strict_types = 1);

	namespace DoctrineMigrations;

	use Doctrine\DBAL\Schema\Schema;
	use Doctrine\Migrations\AbstractMigration;

	final class Version20250306171841 extends AbstractMigration {
		public function getDescription(): string {
			return 'Add item recipes';
		}

		public function up(Schema $schema): void {
			// @formatter:off
			$this->addSql('CREATE TABLE item_recipes (id INT UNSIGNED AUTO_INCREMENT NOT NULL, output_id INT UNSIGNED NOT NULL, amount INT UNSIGNED NOT NULL, INDEX IDX_A834C4E1DE097880 (output_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
			$this->addSql('CREATE TABLE item_recipe_inputs (item_recipe_id INT UNSIGNED NOT NULL, item_id INT UNSIGNED NOT NULL, INDEX IDX_62D316B45502B4E4 (item_recipe_id), INDEX IDX_62D316B4126F525E (item_id), PRIMARY KEY(item_recipe_id, item_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
			$this->addSql('ALTER TABLE item_recipes ADD CONSTRAINT FK_A834C4E1DE097880 FOREIGN KEY (output_id) REFERENCES items (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE item_recipe_inputs ADD CONSTRAINT FK_62D316B45502B4E4 FOREIGN KEY (item_recipe_id) REFERENCES item_recipes (id) ON DELETE CASCADE');
			$this->addSql('ALTER TABLE item_recipe_inputs ADD CONSTRAINT FK_62D316B4126F525E FOREIGN KEY (item_id) REFERENCES items (id) ON DELETE CASCADE');
			// @formatter:on
		}

		public function down(Schema $schema): void {
			$this->addSql('ALTER TABLE item_recipes DROP FOREIGN KEY FK_A834C4E1DE097880');
			$this->addSql('ALTER TABLE item_recipe_inputs DROP FOREIGN KEY FK_62D316B45502B4E4');
			$this->addSql('ALTER TABLE item_recipe_inputs DROP FOREIGN KEY FK_62D316B4126F525E');
			$this->addSql('DROP TABLE item_recipes');
			$this->addSql('DROP TABLE item_recipe_inputs');
		}
	}
