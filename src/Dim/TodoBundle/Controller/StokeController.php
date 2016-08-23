<?php

namespace Dim\TodoBundle\Controller;

use Dim\TodoBundle\Entity\Stoke;
use Dim\TodoBundle\Form\StokeType;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Stoke controller.
 *
 * @Route("/stoke")
 */
class StokeController extends Controller {
	/**
	 * Lists all Stoke entities.
	 *
	 * @Route("/", name="stoke_index")
	 * @Method("GET")
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$stokes = $em->getRepository('DimTodoBundle:Stoke')->findByUserId($this->getUser()->getId());

		return $this->render('DimTodoBundle:stoke:index.html.twig', array(
			'stokes' => $stokes,
		));
	}

	/**
	 * Creates a new Stoke entity.
	 *
	 * @Route("/new", name="stoke_new")
	 * @Method({"GET", "POST"})
	 */
	public function newAction(Request $request) {
		$stoke = new Stoke();
		$stoke->setUserId($this->getUser());
		$form = $this->createForm(StokeType::class, $stoke);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($stoke);
			$em->flush();

			return $this->redirectToRoute('stoke_show', array('id' => $stoke->getId()));
		}

		return $this->render('DimTodoBundle:stoke:new.html.twig', array(
			'stoke' => $stoke,
			'form' => $form->createView(),
		));
	}
	/**
	 * Display portofio return.
	 *
	 * @Route("/chart", name="chart")
	 * @Method({"GET", "POST"})
	 */
	public function chartAction() {
		
		return $this->render('DimTodoBundle:stoke:chart.html.twig');
	}
	

	/**
	 * Display chartdata for portfolio.
	 *
	 * @Route("/chartdata", name="chartdata")
	 * @Method({"GET", "POST"})
	 */

	public function chartdataAction() {

		$em = $this->getDoctrine()->getManager();

		$stokes = $em->getRepository('DimTodoBundle:Stoke')->findByUserId($this->getUser()->getId());

		$resonce_user = $this->get('dim_feedback.YahooApi')->getPortfolioPrice($stokes);
		
		return new JsonResponse($resonce_user);
	}

	/**
	 * Finds and displays a Stoke entity.
	 *
	 * @Route("/{id}", name="stoke_show")
	 * @Method("GET")
	 */
	public function showAction(Stoke $stoke) {
		$deleteForm = $this->createDeleteForm($stoke);

		return $this->render('DimTodoBundle:stoke:show.html.twig', array(
			'stoke' => $stoke,
			'delete_form' => $deleteForm->createView(),
		));
	}

	/**
	 * Displays a form to edit an existing Stoke entity.
	 *
	 * @Route("/{id}/edit", name="stoke_edit")
	 * @Method({"GET", "POST"})
	 */
	public function editAction(Request $request, Stoke $stoke) {
		$deleteForm = $this->createDeleteForm($stoke);
		$editForm = $this->createForm(StokeType::class, $stoke);
		$editForm->handleRequest($request);

		if ($editForm->isSubmitted() && $editForm->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($stoke);
			$em->flush();

			return $this->redirectToRoute('stoke_edit', array('id' => $stoke->getId()));
		}

		return $this->render('DimTodoBundle:stoke:edit.html.twig', array(
			'stoke' => $stoke,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		));
	}

	/**
	 * Deletes a Stoke entity.
	 *
	 * @Route("/{id}", name="stoke_delete")
	 * @Method("DELETE")
	 */
	public function deleteAction(Request $request, Stoke $stoke) {
		$form = $this->createDeleteForm($stoke);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($stoke);
			$em->flush();
		}

		return $this->redirectToRoute('stoke_index');
	}

	/**
	 * Creates a form to delete a Stoke entity.
	 *
	 * @param Stoke $stoke The Stoke entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(Stoke $stoke) {
		return $this->createFormBuilder()
			->setAction($this->generateUrl('stoke_delete', array('id' => $stoke->getId())))
			->setMethod('DELETE')
			->add('Продать', SubmitType::class, ['attr' => ['class' => 'btn btn-danger']])
			->getForm()
		;
	}
}
