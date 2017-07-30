<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comments;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;


class CommentController extends Controller
{

    /**
     * @Route("/comment_by_marker_id/{id}", name="comment_by_marker_id")
     */
    public function commentAction($id)
    {
        $user = $this->getDoctrine()->getRepository(Comments::class);
        $req = $user->findAllComments($id);

        return new Response(json_encode($req));
    }


}