<?php
	namespace App\Import\Importers;

	use App\Entity\HornMelody;
	use App\Entity\HornSong;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\ImportException;
	use App\Import\Models\HornMelodyModel;

	#[AsImporter(priority: 50)]
	class HornMelodyImporter extends AbstractImporter {
		public function __invoke(ImportContext $context): void {
			$path = $context->path('weapons', 'HuntingHornMelodies.json');
			$importData = $this->serializer->deserialize(
				file_get_contents($path),
				HornMelodyModel::class . '[]',
				'json',
			);

			/** @var int[] $visited */
			$visited = [];
			$context->progressStart(count($importData));

			/** @var HornMelodyModel $data */
			foreach ($importData as $data) {
				$context->progressAdvance();

				$melody = $this->entityManager->getRepository(HornMelody::class)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				if (!$melody) {
					$melody = new HornMelody($data->id, $data->notes);
					$this->entityManager->persist($melody);
				} else
					$melody->setNotes($data->notes);

				/** @var int[] $visitedSongs */
				$visitedSongs = [];

				foreach ($data->songs as $songId) {
					/** @var HornSong[] $candidates */
					$candidates = $this->entityManager->getRepository(HornSong::class)->findBy(
						[
							'effectId' => $songId,
						],
					);

					/** @var HornSong|null $song */
					$song = null;

					foreach ($candidates as $candidate) {
						$missing = false;

						foreach ($candidate->getSequence() as $note) {
							if (!in_array($note, $data->notes)) {
								$missing = true;
								break;
							}
						}

						if (!$missing) {
							$song = $candidate;
							break;
						}
					}

					if (!$song)
						throw ImportException::notFound('horn song', 'effectId', $songId, 'horn melodies');

					$melody->addSong($song);
					$visitedSongs[] = $song->getEffectId();
				}

				$melody->removeOrphanedSongsByEffectId($visitedSongs);

				$context->batch->increment(count($data->songs) + 1);
			}

			$context->progressFinish();
			$context->batch->dispatch();

			$this->entityManager->createQueryBuilder()
				->delete(HornMelody::class, 'melody')
				->where('melody.gameId NOT IN (:visited)')
				->setParameter('visited', $visited)
				->getQuery()
				->execute();
		}
	}