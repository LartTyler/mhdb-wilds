<?php
	namespace App\Controller;

	use Symfony\Component\DependencyInjection\Attribute\Autowire;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpKernel\Attribute\AsController;
	use Symfony\Component\Routing\Attribute\Route;

	#[AsController]
	#[Route(path: '/version', name: 'version')]
	class VersionController {
		public function __invoke(
			#[Autowire(env: 'resolve:IMPORT_TIMESTAMP_FILE')]
			string $versionFilePath,
		): Response {
			return new JsonResponse(
				[
					'version' => file_get_contents($versionFilePath),
				],
			);
		}
	}