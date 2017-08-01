<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/send_comment", name="send_comment")
     */
    public function send_commentAction(Request $request)
    {

        $content = $request->request->get('data');
        $marker = $request->request->get('marker');
        $rate = ($request->request->get('rate') > 0) ? $request->request->get('rate') : 0;

        $em = $this->getDoctrine()->getManager();

        $comment = new Comments();
        $comment->setUserId($this->getUser()->getId());
        $comment->setMarkerId($marker);
        $comment->setDate($this->date = new \DateTime());
        $comment->setContent($content);
        $comment->setRate($rate);

        $em->persist($comment);
        $em->flush();

        return new Response('Saved new product with id '.$comment->getId());
    }


}