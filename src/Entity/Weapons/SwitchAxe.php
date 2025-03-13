<?php
	namespace App\Entity\Weapons;

	use App\Entity\SharpnessInterface;
	use App\Entity\SharpnessTrait;
	use App\Entity\Weapon;
	use App\Game\SwitchAxePhial;
	use App\Game\WeaponKind;
	use Doctrine\ORM\Mapping as ORM;
	use Dunglas\DoctrineJsonOdm\Type\JsonDocumentType;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	#[ORM\Entity]
	class SwitchAxe extends Weapon implements SharpnessInterface {
		use SharpnessTrait;

		protected WeaponKind $kind = WeaponKind::SwitchAxe;

		#[SerializedName('phial')]
		#[ORM\Column(type: JsonDocumentType::NAME, options: ['jsonb' => true])]
		protected SwitchAxePhial $switchAxePhial;

		public function getPhial(): SwitchAxePhial {
			return $this->switchAxePhial;
		}

		public function setPhial(SwitchAxePhial $switchAxePhial): static {
			$this->switchAxePhial = $switchAxePhial;
			return $this;
		}
	}
