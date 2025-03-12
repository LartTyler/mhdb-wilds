<?php
	namespace App\Repository;

	use App\Entity\HornSong;
	use App\Game\Note;
	use Doctrine\ORM\EntityRepository;

	class HornSongRepository extends EntityRepository {
		/**
		 * @param int    $id
		 * @param Note[] $sequence
		 *
		 * @return HornSong|null
		 */
		public function findSong(int $id, array $sequence): ?HornSong {
			$candidates = $this->createQueryBuilder('song')
				->where('song.effectId = :id')
				->setParameter('id', $id)
				->getQuery()
				->toIterable();

			/** @var HornSong $candidate */
			foreach ($candidates as $candidate) {
				$candidateSequence = $candidate->getSequence();

				if (count($candidateSequence) !== count($sequence))
					continue;

				$mismatch = false;

				foreach ($sequence as $index => $note) {
					if ($candidateSequence[$index] !== $note) {
						$mismatch = true;
						break;
					}
				}

				if (!$mismatch)
					return $candidate;
			}

			return null;
		}
	}