<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        $rep = $this->getDoctrine()->getRepository(User::class);
            $query = $rep->createQueryBuilder('u')
            ->getQuery();
        $result = $query->getResult(Query::HYDRATE_ARRAY);


        return $this->render('AppBundle:Admin:admin.html.twig', [
            'user' => $result
        ]);
    }
}
