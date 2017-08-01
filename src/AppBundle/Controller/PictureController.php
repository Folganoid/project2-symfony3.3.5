<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Picture;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;



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
}