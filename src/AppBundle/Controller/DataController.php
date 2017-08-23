<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Marker;
use AppBundle\Entity\Picture;
use AppBundle\Form\MarkerType;
use AppBundle\Form\PictureType;
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

        /**
         * new marker form submit
         */
        if ($form->isSubmitted() && $form->isValid()) {

            $marker = $form->getData();
            $marker->setUserId($this->getUser()->getId());

            $em = $this->getDoctrine()->getManager();
            $em->persist($marker);
            $em->flush();

            // redirect to user map page
            return $this->redirect($this->generateUrl('map', array('user_id' => $this->getUser()->getId())));
        }

        $picture = new Picture();
        $form2 = $this->createForm(PictureType::class, $picture);
        $form2->handleRequest($request);

        /**
         * new picture form submit
         */
        if ($form2->isSubmitted() && $form2->isValid()) {

            $picture = $form2->getData();

            if (!$picture->getMarkerId()) throw new \Exception('Empty data');
            if ($picture->getMarkerId()->getUserId() != $this->getUser()->getId()) {
                throw new \Exception('Access denied !');
            }

            $file = $picture->getFilename();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension(); // create unique filename

            $file->move(
                $this->getParameter('pictures_directory'),
                $fileName);

            $picture->setFilename($fileName);
            $picture->setDate($this->date = new \DateTime());
            $picture->setMarkerId($picture->getMarkerId()->getId());

            $em = $this->getDoctrine()->getManager();
            $em->persist($picture);
            $em->flush();

            $uri = $this->generateUrl('map', array('user_id' => $this->getUser()->getId()));
            return $this->redirect($uri);
        }

        return $this->render('AppBundle:Data:data.html.twig', array(
            'form' => $form->createView(),
            'form2' => $form2->createView(),
        ));
    }
}
