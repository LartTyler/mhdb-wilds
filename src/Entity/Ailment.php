<?php
	namespace App\Entity;

	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'ailments')]
	#[AsCrudEntity(
		basePath: '/ailments',
		strict: [
			'protection' => [
				'skills' => [
					'ranks',
				],
			],
		],
	)]
	class Ailment implements EntityInterface {
		use EntityTrait;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		#[Translatable]
		#[ORM\Column(type: Types::TEXT, nullable: true)]
		private ?string $description = null;

		#[ORM\OneToOne(mappedBy: 'ailment', targetEntity: AilmentRecovery::class, cascade: ['all'], fetch: 'EAGER')]
		private AilmentRecovery $recovery;

		#[ORM\OneToOne(mappedBy: 'ailment', targetEntity: AilmentProtection::class, cascade: ['all'], fetch: 'EAGER')]
		private AilmentProtection $protection;

		public function __construct(string $name) {
			$this->name = $name;

			$this->recovery = new AilmentRecovery($this);
			$this->protection = new AilmentProtection($this);
		}

		public function getName(): ?string {
			return $this->name;
		}

		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}

		public function getDescription(): ?string {
			return $this->description;
		}

		public function setDescription(?string $description): static {
			$this->description = $description;
			return $this;
		}

		public function getRecovery(): AilmentRecovery {
			return $this->recovery;
		}

		public function getProtection(): AilmentProtection {
			return $this->protection;
		}
	}