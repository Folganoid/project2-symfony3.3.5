<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Classes\Protect;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CommentController
 * @package AppBundle\Controller
 */
class CommentController extends Controller
{
    /**
     * get comments by marker ID
     * AJAX method
     *
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
     * AJAX method
     */
    public function send_commentAction(Request $request)
    {

        $content = $request->request->get('data');
        $marker = $request->request->get('marker');
        $rate = $request->request->get('rate');

        $em = $this->getDoctrine()->getManager();

        $comment = new Comments();
        $comment->setUserId($this->getUser()->getId());
        $comment->setMarkerId($marker);
        $comment->setDate($this->date = new \DateTime());
        $comment->setContent(Protect::EntrySecure($content));
        $comment->setRate($rate);

        $em->persist($comment);
        $em->flush();

        return new Response('Saved new product with id '.$comment->getId());
    }

    /**
     * delete comment by id
     *
     * @Route("/del_comment/{id}", name="del_comment")
     * AJAX method
     */
    public function delCommentAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comments::class)->find($id);
        $em->remove($comment);
        $em->flush();

        return new Response('Comment with id '.$id. ' is removed');
    }
}