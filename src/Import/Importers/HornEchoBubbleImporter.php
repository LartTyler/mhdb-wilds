<?php
	namespace App\Import\Importers;

	use App\Entity\HornBubble;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\HornBubbleModel;
	use Gedmo\Translatable\Entity\Translation;

	#[AsImporter(priority: 100)]
	class HornEchoBubbleImporter extends AbstractImporter {
		public function __invoke(ImportContext $context): void {
			$path = $context->path('weapons', 'HuntingHornEchoBubbles.json');
			$importData = $this->serializer->deserialize(
				file_get_contents($path),
				HornBubbleModel::class . '[]',
				'json',
			);

			/** @var int[] $visited */
			$visited = [];
			$context->progressStart(count($importData));

			/** @var HornBubbleModel $data */
			foreach ($importData as $data) {
				$bubble = $this->entityManager->getRepository(HornBubble::class)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				if (!$bubble) {
					$bubble = new HornBubble($data->id, $data->kind, $data->getEnglishName());
					$this->entityManager->persist($bubble);
				} else
					$bubble->setKind($data->kind);

				$strings = $this->entityManager->getRepository(Translation::class);

				foreach ($data->names as $locale => $name)
					$strings->translate($bubble, 'name', $locale, $name);

				$visited[] = $data->id;
			}

			$this->entityManager->createQueryBuilder()
				->delete(HornBubble::class, 'bubble')
				->where('bubble.gameId NOT IN (:visited)')
				->setParameter('visited', $visited)
				->getQuery()
				->execute();
		}
	}