<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\DamageValues;
	use App\Entity\Weapon;
	use App\Entity\Weapons\SwitchAxe;
	use App\Game\SwitchAxeDamagePhialInterface;
	use App\Game\SwitchAxeDragonPhial;
	use App\Game\SwitchAxeElementPhial;
	use App\Game\SwitchAxeExhaustPhial;
	use App\Game\SwitchAxeParalyzePhial;
	use App\Game\SwitchAxePhialKind;
	use App\Game\SwitchAxePoisonPhial;
	use App\Game\SwitchAxePowerPhial;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\Weapons\SwitchAxeModel;
	use App\Import\Models\Weapons\WeaponModel;

	/**
	 * @extends AbstractWeaponImporter<SwitchAxe, SwitchAxeModel>
	 */
	#[AsImporter]
	class SwitchAxeImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		public function __invoke(ImportContext $context): void {
			$this->run($context, 'SwitchAxe', SwitchAxe::class);
		}

		protected function process(ImportContext $context, Weapon $weapon, WeaponModel $data): void {
			$phial = match ($data->phial->kind) {
				SwitchAxePhialKind::Power => new SwitchAxePowerPhial(),
				SwitchAxePhialKind::Element => new SwitchAxeElementPhial(),
				SwitchAxePhialKind::Dragon => new SwitchAxeDragonPhial(),
				SwitchAxePhialKind::Exhaust => new SwitchAxeExhaustPhial(),
				SwitchAxePhialKind::Paralyze => new SwitchAxeParalyzePhial(),
				SwitchAxePhialKind::Poison => new SwitchAxePoisonPhial(),
			};

			if ($phial instanceof SwitchAxeDamagePhialInterface) {
				$damage = (new DamageValues())
					->setRaw($data->phial->raw)
					->setDisplay(ceil($data->phial->raw * DamageValues::ELEMENT_COEFFICIENT));

				$phial->setDamage($damage);
			}

			$weapon->setPhial($phial);
		}
	}