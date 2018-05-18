<?php

// src/DAO/PlatformBundle/Controller/AdvertController.php

namespace DAO\PlatformBundle\Controller;

use DAO\PlatformBundle\Entity\Advert;
use DAO\PlatformBundle\Entity\Image;
use DAO\PlatformBundle\Form\AdvertType;
use DAO\PlatformBundle\Form\AdvertEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
	public function __construct()
  	{
    	// Par défaut, la date de l'annonce est la date d'aujourd'hui
   		$this->date = new \Datetime();

  	}
    public function indexAction($page)
    {
    	if ($page < 1)
    	{
    		throw new NotFoudHttpException('Page "'.$page.'"inexistante.');	
    	}
    	// On récupère le repository
	    $repository = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('DAOPlatformBundle:Advert');
	    

	    // On récupère l'entité correspondante à l'id $id
	    $listAdverts = $repository->findAll();

	    // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
	    // ou null si l'id $id  n'existe pas, d'où ce if :
	    if (null === $listAdverts) 
	    {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
	    }
    	

    	return $this->render('DAOPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts,));
    }

     public function viewAction($id)
    {
    	// On récupère le repository
	    $repository = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('DAOPlatformBundle:Advert')
	    ;

	    // On récupère l'entité correspondante à l'id $id
	    $advert = $repository->find($id);

	    // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
	    // ou null si l'id $id  n'existe pas, d'où ce if :
	    if (null === $advert) {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // Le render ne change pas, on passait avant un tableau, maintenant un objet
    return $this->render('DAOPlatformBundle:Advert:view.html.twig', array(
      'advert' => $advert
    ));
    }

  	public function addAction(Request $request)
  	{
      	$advert = new Advert();
	    $form   = $this->get('form.factory')->create(AdvertType::class, $advert);

	    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      //$advert->getImage()->upload();
	      $em = $this->getDoctrine()->getManager();
	      $em->persist($advert);
	      $em->flush();

	      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

	      return $this->redirectToRoute('dao_platform_view', array('id' => $advert->getId()));
	    }

	    return $this->render('DAOPlatformBundle:Advert:add.html.twig', array(
	      'form' => $form->createView(),
	    ));
  	}

  	public function editAction($id, Request $request)
  	{
  		$em = $this->getDoctrine()->getManager();
	    $advert = $em->getRepository('DAOPlatformBundle:Advert')->find($id);
	    
	    if (null === $advert)
	    {
	    	throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");	
	    }

	    $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);

  		if ($request->isMethod('POST')&& $form->handleRequest($request)->isValid())
  		{
	     	$em->flush();

  			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

  			return $this->redirectToRoute('dao_platform_view', array('id' => $advert->getId()));
  		}

  		return $this->render('DAOPlatformBundle:Advert:edit.html.twig', array('advert' => $advert, 'form' => $form->createView()));
  	}

  	public function deleteAction($id, Request $request)
  	{
  		$em = $this->getDoctrine()->getManager();
	    $advert = $em->getRepository('DAOPlatformBundle:Advert')->find($id);

	    if (null === $advert)
	    {
	    	throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");	
	    }

	    $form = $this->get('form.factory')->create();

	    if ($request->isMethod('POST')&& $form->handleRequest($request)->isValid())
  		{
	     	$em->remove($advert);
	     	$em->flush();

  			$request->getSession()->getFlashBag()->add('info', 'Annonce bien supprimée.');

  			return $this->redirectToRoute('dao_platform_home');
  		}

  		return $this->render('DAOPlatformBundle:Advert:delete.html.twig', array(
      		'advert' => $advert,
      		'form'   => $form->createView(),
    	));
  	}

  	public function menuAction()
	{
	    $repository = $this->getDoctrine()
	     ->getManager()
	     ->getRepository('DAOPlatformBundle:Advert');
	    

	    // On récupère l'entité correspondante à l'id $id
	    $listAdverts = $repository->findAll();

	    // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
	    // ou null si l'id $id  n'existe pas, d'où ce if :
	    if (null === $listAdverts) 
	    {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
	    }

	    return $this->render('DAOPlatformBundle:Advert:menu.html.twig', array(
	      // Tout l'intérêt est ici : le contrôleur passe
	      // les variables nécessaires au template !
	      'listAdverts' => $listAdverts
	    ));
	}
}