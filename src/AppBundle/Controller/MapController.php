<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Marker;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MapController
 * @package AppBundle\Controller
 */
class MapController extends Controller
{
    /**
     * @Route("/map/{user_id}", name="map")
     */
    public function mapAction(int $user_id = 0)
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
     * get markers
     *
     * @param int $user_id
     * @return array
     */
    function getMarkerList(int $user_id)
    {
        $repository = $this->getDoctrine()
            ->getRepository(Marker::class);

        $query = $repository->createQueryBuilder('m')
            ->where('m.userId = '.$user_id)
            ->orderBy('m.name', 'ASC')
            ->getQuery();

        return $query->getArrayResult();
    }

}
