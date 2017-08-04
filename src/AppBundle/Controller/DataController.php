<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Marker;
use AppBundle\Form\MarkerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DataController extends Controller
{
    /**
     * @Route("/data", name="data")
     */
    public function dataAction(Request $request)
    {
        $marker = new Marker();
        $form = $this->createForm(MarkerType::class, $marker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $marker = $form->getData();
            $marker->setUserId($this->getUser()->getId());

             $em = $this->getDoctrine()->getManager();
             $em->persist($marker);
             $em->flush();

            return $this->redirectToRoute('data');
        }

        return $this->render('AppBundle:Data:data.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
