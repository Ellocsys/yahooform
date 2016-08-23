<?php

namespace Dim\TodoBundle\Controller;

use Dim\TodoBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller {

	/**
	 *
	 * @Route("/{id}", name="user_show")
	 * @Method("GET")
	 */
	public function showAction(User $user) {

		return $this->render('DimTodoBundle:user:show.html.twig', ['user' => $user]);
	}

}
