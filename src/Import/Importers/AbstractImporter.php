<?php
	namespace App\Import\Importers;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\Serializer\SerializerInterface;

	class AbstractImporter {
		public function __construct(
			protected SerializerInterface $serializer,
			protected EntityManagerInterface $entityManager,
		) {}
	}