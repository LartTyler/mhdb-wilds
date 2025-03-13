<?php
	namespace App\Entity;

	use App\Game\Note;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	#[ORM\Table(name: 'horn_melodies')]
	#[AsCrudEntity(
		basePath: '/weapons/hunting-horn/melodies',
		strict: [
			'songs' => [
				'melodies',
			],
		]
	)]
	class HornMelody implements EntityInterface {
		use EntityTrait;
		use GameIdTrait;

		/**
		 * @type Note[]
		 * @psalm-var list{Note, Note, Note}
		 */
		#[ORM\Column(enumType: Note::class)]
		private array $notes;

		/**
		 * @var Selectable<HornSong>&Collection<HornSong>
		 */
		#[ORM\ManyToMany(targetEntity: HornSong::class, inversedBy: 'melodies')]
		#[ORM\JoinTable(name: 'horn_melody_songs')]
		private Collection&Selectable $songs;

		/**
		 * @param Note[]                       $notes
		 *
		 * @psalm-param list{Note, Note, Note} $notes
		 */
		public function __construct(int $gameId, array $notes) {
			$this->gameId = $gameId;
			$this->notes = $notes;

			$this->songs = new ArrayCollection();
		}

		/**
		 * @return Note[]
		 * @psalm-return list{Note, Note, Note}
		 */
		public function getNotes(): array {
			return $this->notes;
		}

		/**
		 * @param Note[]                       $notes
		 *
		 * @psalm-param list{Note, Note, Note} $notes
		 * @return $this
		 */
		public function setNotes(array $notes): static {
			$this->notes = $notes;
			return $this;
		}

		/**
		 * @return Collection<HornSong>&Selectable<HornSong>
		 */
		public function getSongs(): Selectable&Collection {
			return $this->songs;
		}

		public function addSong(HornSong $song): static {
			if (!$this->getSongs()->contains($song))
				$this->getSongs()->add($song);

			return $this;
		}

		public function removeOrphanedSongsByEffectId(array $effectIds): static {
			foreach ($this->getSongs()->getKeys() as $key) {
				/** @var HornSong $song */
				$song = $this->getSongs()->get($key);

				if (!in_array($song->getEffectId(), $effectIds))
					$this->getSongs()->remove($key);
			}

			return $this;
		}
	}
