<?php
	namespace App\Entity;

	use App\Api\Models\HornSongModel;
	use App\Api\Transformers\HornSongTransformer;
	use App\Game\Note;
	use App\Repository\HornSongRepository;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity(repositoryClass: HornSongRepository::class)]
	#[ORM\Table(name: 'horn_songs')]
	#[AsCrudEntity(
		basePath: '/weapons/hunting-horn/songs',
		transformer: HornSongTransformer::class,
		dtoClass: HornSongModel::class,
		strict: [
			'melodies' => [
				'songs',
			],
		]
	)]
	class HornSong implements EntityInterface {
		use EntityTrait;

		#[ORM\Column(options: ['unsigned' => true])]
		public int $effectId;

		/**
		 * @var Selectable<HornMelody>&Collection<HornMelody>
		 */
		#[ORM\ManyToMany(targetEntity: HornMelody::class, mappedBy: 'songs')]
		private Collection&Selectable $melodies;

		/**
		 * @var Note[]
		 */
		#[ORM\Column(type: Types::JSON, enumType: Note::class)]
		private array $sequence;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		/**
		 * @param int    $effectId
		 * @param Note[] $sequence
		 * @param string $name
		 */
		public function __construct(int $effectId, array $sequence, string $name) {
			$this->effectId = $effectId;
			$this->sequence = $sequence;
			$this->name = $name;

			$this->melodies = new ArrayCollection();
		}

		public function getEffectId(): int {
			return $this->effectId;
		}

		public function setEffectId(int $effectId): static {
			$this->effectId = $effectId;
			return $this;
		}

		/**
		 * @return Note[]
		 */
		public function getSequence(): array {
			return $this->sequence;
		}

		public function setSequence(array $sequence): static {
			$this->sequence = $sequence;
			return $this;
		}

		public function getName(): ?string {
			return $this->name;
		}

		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}

		// Where'd you get those melodies?
		// I don't know, it came to me. As if I'd known it all along.
		public function getMelodies(): Selectable&Collection {
			return $this->melodies;
		}
	}
