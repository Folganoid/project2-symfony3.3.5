<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Marker;
use AppBundle\Entity\User;
use Doctrine\ORM\Internal\Hydration\ArrayHydrator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;



class MapController extends Controller
{

    /**
     * @Route("/map/{user_id}", name="map")
     */
    public function mapAction(int $user_id = 0, SerializerInterface $serializer)
    {
        $user = $this->getDoctrine()->getRepository(User::class);
        $req = $user->findOneBy(
            array('id' => $user_id)
        );
        $username = ($req) ? ($req->getUsername()) : "";

        $markers = $this->getMarkerList($user_id);

        return $this->render('AppBundle:Map:map.html.twig', array(
            'user' => $user_id,
            'username' => $username,
            'markers' => $markers
        ));
    }

    /**
     * @Route("/markers/{id}", name="markers")
     */
    public function markersAction(int $id)
    {
        $markers = json_encode($this->getMarkerList($id));
        return new Response($markers);
    }

    /**
     * @Route("/kml/{filename}", name="kml")
     */
    public function kmlAction($filename)
    {
        $file = new File( __DIR__. '/../../../web/kml/'.$filename);
        return $this->file($file);
    }

    /**
     * get markers
     *
     * @param int $user_id
     * @return array
     */
    protected function getMarkerList(int $user_id)
    {
        $repository = $this->getDoctrine()
            ->getRepository(Marker::class);

        $query = $repository->createQueryBuilder('m')
            ->where('m.userId = '.$user_id)
            ->getQuery();

        return $query->getArrayResult();
    }

}
