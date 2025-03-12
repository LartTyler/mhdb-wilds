<?php
	namespace App\Api\Transformers;

	use App\Api\Models\AmmoModel;
	use App\Api\Models\AutoReloadModel;
	use App\Api\Models\WeaponElementModel;
	use App\Api\Models\WeaponModel;
	use App\Api\Models\Weapons\BowModel;
	use App\Api\Models\Weapons\ChargeBladeModel;
	use App\Api\Models\Weapons\GunlanceModel;
	use App\Api\Models\Weapons\HeavyBowgunModel;
	use App\Api\Models\Weapons\HuntingHornModel;
	use App\Api\Models\Weapons\LightBowgunModel;
	use App\Api\Models\Weapons\StubWeaponModel;
	use App\Api\Models\WeaponStatusModel;
	use App\Entity\HeavyBowgunAmmo;
	use App\Entity\AutoReload;
	use App\Entity\RapidFire;
	use App\Entity\Sharpness;
	use App\Entity\Weapon;
	use App\Entity\WeaponElement;
	use App\Entity\Weapons\Bow;
	use App\Entity\Weapons\ChargeBlade;
	use App\Entity\Weapons\DualBlades;
	use App\Entity\Weapons\GreatSword;
	use App\Entity\Weapons\Gunlance;
	use App\Entity\Weapons\Hammer;
	use App\Entity\Weapons\HeavyBowgun;
	use App\Entity\Weapons\HuntingHorn;
	use App\Entity\Weapons\InsectGlaive;
	use App\Entity\Weapons\Lance;
	use App\Entity\Weapons\LightBowgun;
	use App\Entity\Weapons\LongSword;
	use App\Entity\Weapons\SwitchAxe;
	use App\Entity\Weapons\SwordShield;
	use App\Entity\WeaponStatus;
	use App\Game\WeaponKind;
	use DaybreakStudios\RestBundle\Transformer\AbstractTransformer;
	use DaybreakStudios\RestBundle\Transformer\Traits\CloneNotSupportedTrait;
	use DaybreakStudios\RestBundle\Transformer\Traits\StubDeleteTrait;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\Collection;

	/**
	 * @extends AbstractTransformer<Weapon, WeaponModel>
	 */
	class WeaponTransformer extends AbstractTransformer {
		use StubDeleteTrait;
		use CloneNotSupportedTrait;

		protected function doCreate(object $data): EntityInterface {
			return match (true) {
				$data instanceof BowModel => new Bow($data->name, $data->rarity, $data->damageKind, $data->coatings),
				$data instanceof ChargeBladeModel => new ChargeBlade(
					$data->name,
					$data->rarity,
					$data->damageKind,
					$data->phial,
				),
				$data instanceof GunlanceModel => new Gunlance(
					$data->name,
					$data->rarity,
					$data->damageKind,
					$data->shell,
					$data->shellLevel,
				),
				$data instanceof HeavyBowgunModel => new HeavyBowgun(
					$data->name,
					$data->rarity,
					$data->damageKind,
					$data->deviation,
					$data->specialAmmo,
				),
				$data instanceof HuntingHornModel => new HuntingHorn(
					$data->name,
					$data->rarity,
					$data->damageKind,
					$data->melody,
				),
				$data instanceof LightBowgunModel => new LightBowgun(
					$data->name,
					$data->rarity,
					$data->damageKind,
					$data->deviation,
				),
				$data instanceof StubWeaponModel => $this->createFromStub($data),
				default => throw new \LogicException('Unsupported weapon model ' . $data::class),
			};
		}

		protected function createFromStub(StubWeaponModel $data): Weapon {
			$class = match ($data->kind) {
				WeaponKind::DualBlades => DualBlades::class,
				WeaponKind::GreatSword => GreatSword::class,
				WeaponKind::Hammer => Hammer::class,
				WeaponKind::InsectGlaive => InsectGlaive::class,
				WeaponKind::Lance => Lance::class,
				WeaponKind::LongSword => LongSword::class,
				WeaponKind::SwitchAxe => SwitchAxe::class,
				WeaponKind::SwordAndShield => SwordShield::class,
				default => throw new \LogicException('Unsupported model kind ' . $data->kind->value),
			};

			return new $class($data->kind, $data->rarity, $data->damageKind);
		}

		protected function doUpdate(object $data, EntityInterface $entity): void {
			if (isset($data->name))
				$entity->setName($data->name);

			if (isset($data->rarity))
				$entity->setRarity($data->rarity);

			if (isset($data->damageKind))
				$entity->setDamageKind($data->damageKind);

			if (isset($data->defenseBonus))
				$entity->setDefenseBonus($data->defenseBonus);

			if ($data->exists('elderseal'))
				$entity->setElderseal($data->elderseal);

			if (isset($data->affinity))
				$entity->setAffinity($data->affinity);

			if (isset($data->slots))
				$entity->setSlots($data->slots);

			if (isset($data->damage)) {
				$damage = $entity->getDamage();

				if (isset($data->damage->raw))
					$damage->setRaw($data->damage->raw);

				if (isset($data->damage->display))
					$damage->setDisplay($data->damage->display);
			}

			if (isset($data->skills)) {
				$skills = $entity->getSkills();
				$skills->clear();

				foreach ($data->skills as $skill)
					$skills->add($skill);
			}

			if (isset($data->specials)) {
				$specials = $entity->getSpecials();
				$specials->clear();

				foreach ($data->specials as $item) {
					$special = match (true) {
						$item instanceof WeaponElementModel => new WeaponElement(
							$entity,
							$item->element,
							$item->hidden,
						),
						$item instanceof WeaponStatusModel => new WeaponStatus($entity, $item->status, $item->hidden),
						default => throw new \LogicException('Unsupported special kind ' . $item::class),
					};

					$special->getDamage()
						->setRaw($item->damage->raw)
						->setDisplay($item->damage->display);

					$specials->add($special);
				}
			}

			if (isset($data->sharpness)) {
				$sharpnesses = $entity->getSharpness();
				$sharpnesses->clear();

				foreach ($data->sharpness as $item) {
					$sharpness = new Sharpness($entity);
					$sharpness
						->setRed($item->red)
						->setOrange($item->orange)
						->setYellow($item->yellow)
						->setGreen($item->green)
						->setBlue($item->blue)
						->setWhite($item->white)
						->setPurple($item->purple);

					$sharpnesses->add($sharpness);
				}
			}

			match (true) {
				$entity instanceof Bow => $this->doUpdateBow($data, $entity),
				$entity instanceof ChargeBlade => $this->doUpdateChargeBlade($data, $entity),
				$entity instanceof Gunlance => $this->doUpdateGunlance($data, $entity),
				$entity instanceof HeavyBowgun => $this->doUpdateHeavyBowgun($data, $entity),
				$entity instanceof HuntingHorn => $this->doUpdateHuntingHorn($data, $entity),
				$entity instanceof LightBowgun => $this->doUpdateLightBowgun($data, $entity),
			};
		}

		protected function doUpdateBow(WeaponModel $data, Bow $entity): void {
			assert($data instanceof BowModel);

			if (isset($data->coatings))
				$entity->setCoatings($data->coatings);
		}

		protected function doUpdateChargeBlade(WeaponModel $data, ChargeBlade $entity): void {
			assert($data instanceof ChargeBladeModel);

			if (isset($data->phial))
				$entity->setPhial($data->phial);
		}

		protected function doUpdateGunlance(WeaponModel $data, Gunlance $entity): void {
			assert($data instanceof GunlanceModel);

			if (isset($data->shell))
				$entity->setShell($data->shell);

			if (isset($data->shellLevel))
				$entity->setShellLevel($data->shellLevel);
		}

		protected function doUpdateHeavyBowgun(WeaponModel $data, HeavyBowgun $entity): void {
			assert($data instanceof HeavyBowgunModel);

			if (isset($data->deviation))
				$entity->setDeviation($data->deviation);

			if (isset($data->specialAmmo))
				$entity->setSpecialAmmo($data->specialAmmo);

			if (isset($data->ammo))
				$this->doUpdateAmmo($data->ammo, $entity->getAmmo());

			if (isset($data->autoReload))
				$this->doUpdateAutoReload($data->autoReload, $entity->getAutoReload());
		}

		protected function doUpdateHuntingHorn(WeaponModel $data, HuntingHorn $entity): void {
			assert($data instanceof HuntingHornModel);

			if (isset($data->melody))
				$entity->setMelody($data->melody);
		}

		protected function doUpdateLightBowgun(WeaponModel $data, LightBowgun $entity): void {
			assert($data instanceof LightBowgunModel);

			if (isset($data->deviation))
				$entity->setDeviation($data->deviation);

			if (isset($data->ammo))
				$this->doUpdateAmmo($data->ammo, $entity->getAmmo());

			if (isset($data->autoReload))
				$this->doUpdateAutoReload($data->autoReload, $entity->getAutoReload());

			if (isset($data->rapidFire)) {
				$entity->getRapidFire()->clear();

				foreach ($data->rapidFire as $item)
					$entity->getRapidFire()->add(new RapidFire($item->ammo, $item->level));
			}
		}

		/**
		 * @param AmmoModel[]                 $data
		 * @param Collection<HeavyBowgunAmmo> $collection
		 *
		 * @return void
		 */
		protected function doUpdateAmmo(array $data, Collection $collection): void {
			$collection->clear();

			foreach ($data as $item) {
				$ammo = new HeavyBowgunAmmo($item->kind);
				$ammo->setCapacities($item->capacities);

				$collection->add($ammo);
			}
		}

		/**
		 * @param AutoReloadModel[]      $data
		 * @param Collection<AutoReload> $collection
		 *
		 * @return void
		 */
		protected function doUpdateAutoReload(array $data, Collection $collection): void {
			$collection->clear();

			foreach ($data as $item)
				$collection->add(new AutoReload($item->ammo, $item->level));
		}
	}
