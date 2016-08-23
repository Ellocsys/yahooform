<?php

namespace Dim\TodoBundle\Controller;

use Dim\TodoBundle\Entity\User;
use Dim\TodoBundle\Form\ForgetType;
use Dim\TodoBundle\Form\LoginType;
use Dim\TodoBundle\Form\UserType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Todo controller.
 *
 * @Route("/")
 */

class DefaultController extends Controller {

	/**
	 * @Route("/register", name="user_registration")
	 */
	public function registerAction(Request $request) {
		$session = $request->getSession();
		$user = new User();
		$form = $this->createForm(UserType::class, $user);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
			$user->setPassword($password);
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			try {
				$em->flush();
			} catch (UniqueConstraintViolationException $e) {
				$session->getFlashBag()->add('alert', 'Пользователь с таким именем или емаил уже существует');
			}
			return $this->redirectToRoute('login');
		}

		return $this->render('DimTodoBundle:registration:register.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/", name="login")
	 */
	public function loginAction(Request $request) {

		$authenticationUtils = $this->get('security.authentication_utils');
		$error = $authenticationUtils->getLastAuthenticationError();
		$last_username = $authenticationUtils->getLastUsername();
		$user = new User();
		$user->setUsername($authenticationUtils->getLastUsername());
		$form = $this->createForm(LoginType::class, $user);

		if ($form->isSubmitted() && $form->isValid()) {

			return $this->redirectToRoute('stoke_index');
		}

		return $this->render(
			'DimTodoBundle:registration:login.html.twig',
			array(
				'error' => $error,
				'form' => $form->createView(),
				'last_username' => $last_username,
			)
		);

	}
	
	/**
	 * @Route("/logout", name="user_logout")
	 */

	public function logoutAction() {

		$this->get('security.context')->setToken(null);
		$this->get('request')->getSession()->invalidate();

		return $this->redirect($this->generateUrl('login'));
	}

	/**
	 * @Route("/forget", name="user_repasword")
	 */
	public function forgetAction(Request $request) {

		$session = $request->getSession();
		$form = $this->createForm(ForgetType::class);
		$form->handleRequest($request);
		$data = $form->getData();

		if ($form->isValid()) {
			$repository = $this->getDoctrine()->getRepository('DimTodoBundle:User');
			$user = $repository->findOneBy(['email' => $data->getEmail()]);
			if (!$user) {
				$session->getFlashBag()->add('alert', 'Пользователь с такой почтой не существует');
				return $this->redirectToRoute('login');
			}
			return $this->forward('DimTodoBundle:Default:repass', [$user]);
		}
		return $this->render('DimTodoBundle:registration:forget.html.twig', [
			'form' => $form->createView(),
		]);
	}


	/**
	 * @Route("/forget/{id}", name="user_repass")
	 */

	public function repassAction(User $user, Request $request) {

		$session = $request->getSession();

		$random = $this->get('dim_feedback.generator')->randomPassword();

		$user->setPassword($random);

		$sender_email = $this->container->getParameter('mailer_user'); // узнаем из параметров адрес пользователя от имени которого будет отправленно письмо
		$resonce_user = $this->get('dim_feedback.mailer')->sendEmailUser($user->getEmail(), $sender_email, $user); // отправляем письмо пользователю
		if ($resonce_user) {
			$session->getFlashBag()->add('alert', 'На указанную почту отправленн новый пароль');
		}
		$password = $this->get('security.password_encoder')->encodePassword($user, $random);
		$user->setPassword($password);

		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();

		return $this->redirectToRoute('user_logout');
	}

}
