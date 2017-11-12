<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
}