<?php

namespace AppBundle\EventListener;

use AppBundle\Controller\IAuthenticatedController;
use AppBundle\Service\AuthService;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthListener
{
	/** @var AuthService */
	private $authService;

	public function __construct(AuthService $authService)
	{
		$this->authService = $authService;
	}

	public function onKernelController(FilterControllerEvent $event)
	{
		$controller = $event->getController();

		/*
		 * $controller passed can be either a class or a Closure.
		 * This is not usual in Symfony but it may happen.
		 * If it is a class, it comes in array format
		 */
		if (!is_array($controller)) {
			return;
		}

		if ($controller[0] instanceof IAuthenticatedController) {
			$request = $event->getRequest();
			$jwt = $request->get('jwt');
			if (!$this->authService->tokenValid($jwt)) {
				throw new AccessDeniedHttpException('Authorisation required to access this data');
			}
		}
	}
}