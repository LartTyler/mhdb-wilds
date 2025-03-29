<?php

	namespace App\Repository;

	use App\Entity\Weapon;
	use Doctrine\ORM\EntityRepository;

	class WeaponRepository extends EntityRepository {
		/**
		 * @template T of Weapon
		 *
		 * @param class-string<T> $class
		 * @param int             $gameId
		 *
		 * @return Weapon|null
		 */
		public function findOneByGameId(string $class, int $gameId): ?Weapon {
			return $this->createQueryBuilder('weapon')
				->where('weapon INSTANCE OF ' . $class)
				->andWhere('weapon.gameId = :gameId')
				->setParameter('gameId', $gameId)
				->getQuery()
				->getOneOrNullResult();
		}
	}
