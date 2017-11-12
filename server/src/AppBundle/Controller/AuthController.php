<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Helper\Response;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use \Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AuthController
 * @Route("/api")
 * @package AppBundle\Controller
 */
class AuthController extends Controller
{
	/**
	 * @Route("/login")
	 * @Method("POST")
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function loginAction(Request $request)
	{
		$r = new Response();

		try {
			$jwt = $this->get('auth_service')->login(
				$request->request->get('login'),
				$request->request->get('password')
			);

			$r->addData('jwt', $jwt);
		} catch (\Exception $exception) {
			$r->setError("Тыдыц. " . $exception->getMessage());
		}

		return $r->response();
	}

	/**
	 * @Route("/register")
	 * @Method("POST")
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function registerAction(Request $request)
	{
		$response = new Response();

		try {
			$this->get('auth_service')->register(
				$request->request->get('login'),
				$request->request->get('password')
			);
		} catch (\Exception $exception) {
			$response->setError("Логин уже занят", 422);
		}

		return $response->response();
	}
}