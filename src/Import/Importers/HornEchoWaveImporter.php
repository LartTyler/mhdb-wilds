<?php
	namespace App\Import\Importers;

	use App\Entity\HornWave;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\HornWaveModel;
	use Gedmo\Translatable\Entity\Translation;

	#[AsImporter(priority: 100)]
	class HornEchoWaveImporter extends AbstractImporter {
		public function __invoke(ImportContext $context): void {
			$path = $context->path('weapons', 'HuntingHornEchoWaves.json');
			$importData = $this->serializer->deserialize(file_get_contents($path), HornWaveModel::class . '[]', 'json');

			/** @var int[] $visited */
			$visited = [];
			$context->progressStart(count($importData));

			/** @var HornWaveModel $data */
			foreach ($importData as $data) {
				$wave = $this->entityManager->getRepository(HornWave::class)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				if (!$wave) {
					$wave = new HornWave($data->id, $data->kind, $data->getEnglishName());
					$this->entityManager->persist($wave);
				} else
					$wave->setKind($data->kind);

				$strings = $this->entityManager->getRepository(Translation::class);

				foreach ($data->names as $locale => $name)
					$strings->translate($wave, 'name', $locale, $name);

				$visited[] = $data->id;
			}

			$this->entityManager->createQueryBuilder()
				->delete(HornWave::class, 'wave')
				->where('wave.gameId NOT IN (:visited)')
				->setParameter('visited', $visited)
				->getQuery()
				->execute();
		}
	}