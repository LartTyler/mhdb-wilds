<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\HornBubble;
	use App\Entity\HornMelody;
	use App\Entity\HornWave;
	use App\Entity\Weapon;
	use App\Entity\Weapons\HuntingHorn;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\ImportException;
	use App\Import\Models\Weapons\HuntingHornModel;
	use App\Import\Models\Weapons\WeaponModel;

	/**
	 * @extends AbstractWeaponImporter<HuntingHorn, HuntingHornModel>
	 */
	#[AsImporter]
	class HuntingHornImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		public function __invoke(ImportContext $context): void {
			$this->run($context, 'HuntingHorn', HuntingHorn::class);
		}

		protected function process(ImportContext $context, Weapon $weapon, WeaponModel $data): void {
			$melody = $this->entityManager->getRepository(HornMelody::class)->findOneBy(
				[
					'gameId' => $data->melodyId,
				],
			);

			if (!$melody)
				throw ImportException::notFound('melody', 'gameId', $data->melodyId, 'hunting horns');

			$weapon->setMelody($melody);

			$bubble = $this->entityManager->getRepository(HornBubble::class)->findOneBy(
				[
					'gameId' => $data->bubbleId,
				],
			);

			if (!$bubble)
				throw ImportException::notFound('bubble', 'gameId', $data->bubbleId, 'hunting horns');

			$weapon->setEchoBubble($bubble);

			if ($data->waveId !== null) {
				$wave = $this->entityManager->getRepository(HornWave::class)->findOneBy(
					[
						'gameId' => $data->waveId,
					],
				);

				if (!$wave)
					throw ImportException::notFound('wave', 'gameId', $data->waveId, 'hunting horns');

				$weapon->setEchoWave($wave);
			}
		}
	}