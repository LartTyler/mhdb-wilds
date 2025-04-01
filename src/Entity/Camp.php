<?php
	namespace App\Entity;

	use App\Game\Risk;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'camps')]
	#[AsCrudEntity(
		basePath: '/locations/camps',
		strict: [
			'location' => [
				'camps',
			],
		]
	)]
	class Camp implements EntityInterface {
		use EntityTrait;
		use GameIdTrait;

		#[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'camps')]
		#[ORM\JoinColumn(onDelete: 'CASCADE')]
		private Location $location;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $zone;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $floor;

		#[ORM\Column(enumType: Risk::class)]
		private Risk $risk;

		#[ORM\Embedded]
		private Position $position;

		public function __construct(int $gameId, Location $location, string $name, int $zone) {
			$this->gameId = $gameId;
			$this->name = $name;
			$this->location = $location;
			$this->zone = $zone;
			$this->position = new Position();
		}

		public function getLocation(): Location {
			return $this->location;
		}

		public function getName(): ?string {
			return $this->name;
		}

		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}

		public function getZone(): int {
			return $this->zone;
		}

		public function setZone(int $zone): static {
			$this->zone = $zone;
			return $this;
		}

		public function getFloor(): int {
			return $this->floor;
		}

		public function setFloor(int $floor): static {
			$this->floor = $floor;
			return $this;
		}

		public function getRisk(): Risk {
			return $this->risk;
		}

		public function setRisk(Risk $risk): static {
			$this->risk = $risk;
			return $this;
		}

		public function getPosition(): Position {
			return $this->position;
		}
	}
