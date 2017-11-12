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
	 */
	public function loginAction(Request $request)
	{
		$login = $request->request->get('login');
		$password = $request->request->get('password');
		var_dump($login, $password);die;

	}

	/**
	 * @Route("/register")
	 * @Method("POST")
	 */
	public function registerAction(UserPasswordEncoderInterface $encoder, Request $request)
	{
		$response = new Response();

		/** @var EntityManagerInterface $em */
		$em = $this->get('doctrine.orm.default_entity_manager');

		$login = $request->request->get('login');
		$password = $request->request->get('password');
		// whatever *your* User object is
		$user = new User();
		$user->setLogin($login);
		$encoded = $encoder->encodePassword($user, $password);

		$user->setPassword($encoded);

		try {
			$em->persist($user);
			$em->flush();
		} catch (\Exception $exception) {
			$response->setError("Login exists", 422);
		}

		return $response->response();
	}
}