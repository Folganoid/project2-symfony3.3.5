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

        $userListTMP = $user->findAll();
        $userList = [];

        for ($i = 0; $i < count($userListTMP); $i++) {
            $userList[] = [$userListTMP[$i]->getId(), $userListTMP[$i]->getUsername()];
        };

        $username = ($req) ? ($req->getUsername()) : "";
        $userId = ($this->getUser()) ? $this->getUser()->getId() : NULL;
        $markers = $this->getMarkerList($user_id);

        return $this->render('AppBundle:Map:map.html.twig', array(
            'user' => $user_id,
            'iduser' => $userId,
            'username' => $username,
            'markers' => $markers,
            'userlist' => $userList,
            'admin' => ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) ? 1 : 0,
        ));
    }

    /**
     * @Route("/markers/{id}", name="markers")
     * AJAX method
     */
    public function markersAction(int $id)
    {
        $markers = json_encode($this->getMarkerList($id));
        return new Response($markers);
    }

    /**
     * get markers by user id
     *
     * @param int $user_id
     * @return array
     */
    function getMarkerList(int $user_id)
    {
        $repository = $this->getDoctrine()
            ->getRepository(Marker::class);

        $query = $repository->createQueryBuilder('m')
            ->where('m.userId = ' . $user_id)
            ->orderBy('m.name', 'ASC')
            ->getQuery();

        return $query->getArrayResult();
    }
}
