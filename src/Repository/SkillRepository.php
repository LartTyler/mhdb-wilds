<?php
	namespace App\Repository;

	use App\Entity\Skill;
	use Doctrine\ORM\EntityRepository;

	class SkillRepository extends EntityRepository {
		public function findOneByGameId(int $gameId): ?Skill {
			return $this->findOneBy(
				[
					'gameId' => $gameId,
				],
			);
		}
	}