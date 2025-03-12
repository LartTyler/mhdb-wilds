<?php
	namespace App\Import\Importers;

	use App\Entity\HornSong;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\HornSongModel;
	use Gedmo\Translatable\Entity\Translation;

	#[AsImporter(priority: 100)]
	class HornSongImporter extends AbstractImporter {
		public function __invoke(ImportContext $context): void {
			$path = $context->path('weapons', 'HuntingHornSongs.json');
			$importData = $this->serializer->deserialize(file_get_contents($path), HornSongModel::class . '[]', 'json');

			/** @var int[] $visited */
			$visited = [];
			$context->progressStart(count($importData));

			/** @var HornSongModel $data */
			foreach ($importData as $data) {
				$context->progressAdvance();

				$song = $this->entityManager->getRepository(HornSong::class)->findSong($data->id, $data->notes);

				if (!$song) {
					$song = new HornSong($data->id, $data->notes, $data->getEnglishName());
					$this->entityManager->persist($song);
				}

				$strings = $this->entityManager->getRepository(Translation::class);

				foreach ($data->names as $locale => $name)
					$strings->translate($song, 'name', $locale, $name);

				$visited[] = $data->id;

				$context->batch->increment(count($data->names) + 1);
			}

			$context->progressFinish();
			$context->batch->dispatch();

			$this->entityManager->createQueryBuilder()
				->delete(HornSong::class, 'song')
				->where('song.effectId NOT IN (:visited)')
				->setParameter('visited', $visited)
				->getQuery()
				->execute();
		}
	}