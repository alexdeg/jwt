<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @Route("/api")
 * @package AppBundle\Controller
 */
class DefaultController extends Controller implements IAuthenticatedController
{
	/**
	 * @Route("/", name="homepage")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function indexAction(Request $request)
    {
    	var_dump(1231);die;
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
