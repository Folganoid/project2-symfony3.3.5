<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Picture;
use AppBundle\Form\ImgEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class PictureController
 * @package AppBundle\Controller
 */
class PictureController extends Controller
{

    /**
     * @Route("/picture_by_marker_id/{id}", name="picture_by_marker_id")
     */
    public function pictureAction($id, SerializerInterface $serializer)
    {
        $repository = $this->getDoctrine()->getRepository(Picture::class);

        $picture = $repository->findBy(
            array('markerId' => $id)
        );

        $jsonContent = $serializer->serialize($picture, 'json');

        return new Response($jsonContent);
    }

    /**
     * @Route("/img_edit/{id}", name="image_edit")
     */
    public function imgEditAction($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Picture::class);

        $picture = $repository->findOneBy(
            array('id' => $id)
        );

        if (!$picture) {
            throw $this->createNotFoundException('Unable to find Picture.');
        }

        $form = $this->createForm(ImgEditType::class, new Picture(), ['id' => $id, 'name' => $picture->getName(), 'markerId' => $picture->getMarkerId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $marker = $form->getData();

            if (!$marker) {
                throw $this->createNotFoundException('Unable to find marker.');
            }

            if ($marker->getMarkerId()->getUserId() != $this->getUser()->getId()) {
                throw new \Exception('Access denied');
            }

            $em = $this->getDoctrine()->getManager();
            $pic = $em->getRepository(Picture::class)->find($id);

            if ($form->get('save')->isClicked()) {

                $pic->setName($marker->getName());
                $pic->setMarkerId($marker->getMarkerId()->getId());

                $em->persist($pic);
                $em->flush();
            }

            if ($form->get('delete')->isClicked()) {
                $fs = new Filesystem();
                $fs->remove([$this->getParameter('pictures_directory') . '/' . $picture->getFilename()]);
                $em->remove($pic);
                $em->flush();
            }


            return $this->redirect($this->generateUrl('map', array('user_id' => $this->getUser()->getId())));
        }

        return $this->render('AppBundle:Data:img_edit.html.twig', array(
            'form' => $form->createView(),
            'picture' => '/img/' . $picture->getFilename()
        ));



    }

}