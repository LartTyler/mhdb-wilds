<?php
	namespace App\Import\Importers;

	use App\Entity\WeaponSeries;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\WeaponSeriesModel;
	use Gedmo\Translatable\Entity\Translation;

	#[AsImporter(priority: 100)]
	class WeaponSeriesImporter extends AbstractImporter {
		public function __invoke(ImportContext $context): void {
			$path = $context->path('WeaponSeries.json');
			$importData =
				$this->serializer->deserialize(file_get_contents($path), WeaponSeriesModel::class . '[]', 'json');

			/** @var int[] $visited */
			$visited = [];
			$strings = $this->entityManager->getRepository(Translation::class);

			$context->progressStart(count($importData));

			/** @var WeaponSeriesModel $data */
			foreach ($importData as $data) {
				$context->progressAdvance();
				$visited[] = $data->id;

				$series = $this->entityManager->getRepository(WeaponSeries::class)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				if (!$series) {
					$series = new WeaponSeries($data->id, $data->getEnglishName());
					$this->entityManager->persist($series);
				}

				foreach ($data->names as $locale => $name)
					$strings->translate($series, 'name', $locale, $name);

				$context->batch->increment(count($data->names) + 1);
			}

			$context->batch->dispatch();

			$this->entityManager->createQueryBuilder()
				->delete(WeaponSeries::class, 'series')
				->where('series.gameId NOT IN (:visited)')
				->setParameter('visited', $visited)
				->getQuery()
				->execute();

			$context->progressFinish();
		}
	}