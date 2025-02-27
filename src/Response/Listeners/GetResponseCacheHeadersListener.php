<?php
	namespace App\Response\Listeners;

	use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpKernel\Event\ResponseEvent;

	#[AsEventListener]
	class GetResponseCacheHeadersListener {
		public function __invoke(ResponseEvent $event): void {
			if ($event->getRequest()->getMethod() !== Request::METHOD_GET)
				return;

			$event->getResponse()
				->setPublic()
				->setMaxAge(3600);
		}
	}