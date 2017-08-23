<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Marker;
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
     * AJAX method
     * return JSON
     */
    public function pictureAction(int $id, SerializerInterface $serializer)
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
    public function imgEditAction(int $id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Picture::class);
        $picture = $repository->findOneBy(
            array('id' => $id)
        );

        if (!$picture) {
            throw $this->createNotFoundException('Unable to find Picture.');
        }

        $form = $this->createForm(ImgEditType::class, new Picture(), [
                'id' => $id, 'name' => $picture->getName(),
                'markerId' => $picture->getMarkerId(),
                'ownerId' => $this->getOwnerId($picture->getMarkerId())
            ]
        );

        $form->handleRequest($request);

        /**
         * image edit form submit
         */
        if ($form->isSubmitted()) {

            $marker = $form->getData();

            if (!$marker) {
                throw $this->createNotFoundException('Unable to find marker.');
            }

            if ($marker->getMarkerId()->getUserId() != $this->getUser()->getId()) {
                if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                    throw new \Exception('Access denied');
                }
            }

            $em = $this->getDoctrine()->getManager();
            $pic = $em->getRepository(Picture::class)->find($id);

            /**
             * save button
             */
            if ($form->get('save')->isClicked()) {

                $pic->setName($marker->getName());
                $pic->setMarkerId($marker->getMarkerId()->getId());

                $em->persist($pic);
                $em->flush();
            }

            /**
             * delete button
             */
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

    /**
     * get user id by marker id
     *
     * @param $markerId
     * @return mixed
     */
    private function getOwnerId(int $markerId)
    {
        $repository = $this->getDoctrine()->getRepository(Marker::class);
        $marker = $repository->findOneBy(
            array('id' => $markerId)
        );
        return $marker->getUserId();
    }

}