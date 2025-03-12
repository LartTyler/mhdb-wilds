<?php
	namespace App\Entity;

	use App\Game\HornWaveKind;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'hunting_horn_waves')]
	#[AsCrudEntity(
		basePath: '/weapons/hunting-horn/waves'
	)]
	class HornWave implements EntityInterface {
		use EntityTrait;
		use GameIdTrait;

		#[ORM\Column(enumType: HornWaveKind::class)]
		private HornWaveKind $kind;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		public function __construct(int $gameId, HornWaveKind $kind, string $name) {
			$this->gameId = $gameId;
			$this->kind = $kind;
			$this->name = $name;
		}

		public function getKind(): HornWaveKind {
			return $this->kind;
		}

		public function setKind(HornWaveKind $kind): static {
			$this->kind = $kind;
			return $this;
		}

		public function getName(): ?string {
			return $this->name;
		}

		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}
	}