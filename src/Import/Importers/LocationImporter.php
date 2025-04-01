<?php
	namespace App\Import\Importers;

	use App\Entity\Camp;
	use App\Entity\Location;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\LocationModel;
	use Gedmo\Translatable\Entity\Translation;

	#[AsImporter(priority: 100)]
	class LocationImporter extends AbstractImporter {
		public function __invoke(ImportContext $context): void {
			$path = $context->path('Stage.json');
			$importData = $this->serializer->deserialize(file_get_contents($path), LocationModel::class . '[]', 'json');

			$context->progressStart(count($importData));

			/** @var int[] $visited */
			$visited = [];

			/** @var LocationModel $data */
			foreach ($importData as $data) {
				$context->progressAdvance();
				$visited[] = $data->id;

				$location = $this->entityManager->getRepository(Location::class)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				if (!$location) {
					$location = new Location($data->id, $data->getEnglishName(), $data->areas);
					$this->entityManager->persist($location);
				} else
					$location->setZoneCount($data->areas);

				$strings = $this->entityManager->getRepository(Translation::class);

				foreach ($data->names as $locale => $name)
					$strings->translate($location, 'name', $locale, $name);

				/** @var int[] $visitedCamps */
				$visitedCamps = [];

				foreach ($data->camps as $campData) {
					$camp = $location->getCamp($campData->area);
					$visitedCamps[] = $campData->id;

					if (!$camp) {
						$camp = new Camp($campData->id, $location, $campData->getEnglishName(), $campData->area);
						$this->entityManager->persist($camp);
					}

					$camp
						->setFloor($campData->floor)
						->setRisk($campData->risk);

					$camp->getPosition()
						->setX($campData->position->x)
						->setY($campData->position->y)
						->setZ($campData->position->z);

					foreach ($campData->names as $locale => $name)
						$strings->translate($camp, 'name', $locale, $name);
				}

				$location->removeOrphanedCampsByGameId($visitedCamps);
				$context->batch->increment(count($data->camps) + 1);
			}

			$context->batch->dispatch();

			$this->entityManager->createQueryBuilder()
				->delete(Location::class, 'location')
				->where('location.gameId NOT IN (:visited)')
				->setParameter('visited', $visited)
				->getQuery()
				->execute();

			$context->progressFinish();
		}
	}