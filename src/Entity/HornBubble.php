<?php
	namespace App\Entity;

	use App\Game\HornBubbleKind;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'hunting_horn_bubbles')]
	#[AsCrudEntity(
		basePath: '/weapons/hunting-horn/bubbles'
	)]
	class HornBubble implements EntityInterface {
		use EntityTrait;
		use GameIdTrait;

		#[ORM\Column(unique: true, enumType: HornBubbleKind::class)]
		public HornBubbleKind $kind;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		public ?string $name;

		public function __construct(int $gameId, HornBubbleKind $kind, string $name) {
			$this->gameId = $gameId;
			$this->kind = $kind;
			$this->name = $name;
		}

		public function getKind(): HornBubbleKind {
			return $this->kind;
		}

		public function setKind(HornBubbleKind $kind): static {
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