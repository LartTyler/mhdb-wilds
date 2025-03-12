<?php
	namespace App\Api\Transformers;

	use App\Api\Models\HornSongModel;
	use App\Entity\HornSong;
	use DaybreakStudios\RestBundle\Transformer\AbstractTransformer;
	use DaybreakStudios\RestBundle\Transformer\Traits\CloneNotSupportedTrait;
	use DaybreakStudios\RestBundle\Transformer\Traits\StubDeleteTrait;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;

	/**
	 * @extends AbstractTransformer<HornSong, HornSongModel>
	 */
	class HornSongTransformer extends AbstractTransformer {
		use StubDeleteTrait;
		use CloneNotSupportedTrait;

		protected function doCreate(object $data): EntityInterface {
			return new HornSong($data->melody, $data->sequence, $data->duration, $data->effects);
		}

		protected function doUpdate(object $data, EntityInterface $entity): void {
			if (isset($data->sequence))
				$entity->setSequence($data->sequence);

			if (isset($data->duration))
				$entity->setDuration($data->duration);

			if (isset($data->effects))
				$entity->setName($data->effects);

			if (isset($data->personal))
				$entity->setPersonal($data->personal);
		}
	}
