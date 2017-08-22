<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 19.08.17
 * Time: 19:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Marker;
use AppBundle\Entity\Picture;
use AppBundle\Form\MarkerEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class MarkerController
 * @package AppBundle\Controller
 */
class MarkerController extends Controller
{
    /**
     * @Route("/marker_edit/{id}", name="marker_edit")
     */
    public function markerEditAction($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Marker::class);

        $marker = $repository->findOneBy(
            array('id' => $id)
        );

        if (!$marker) {
            throw $this->createNotFoundException('Unable to find Marker.');
        }

        if ($marker->getUserId() != $this->getUser()->getId()) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw new \Exception('Access denied');
            }
        }

        $form = $this->createForm(MarkerEditType::class, new Marker(), ['name' => $marker->getName(), 'coordX' => $marker->getCoordX(), 'coordY' => $marker->getCoordY()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $markerForm = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $mrk = $em->getRepository(Marker::class)->find($id);

            if ($form->get('save')->isClicked()) {

                $mrk->setName($markerForm->getName());
                $mrk->setCoordX($markerForm->getCoordX());
                $mrk->setCoordY($markerForm->getCoordY());

                $em->persist($mrk);
                $em->flush();
            }

            if ($form->get('delete')->isClicked()) {

                $picture = $this->getDoctrine()->getRepository(Picture::class)->findBy(
                    array('markerId' => $id)
                );

                $fileList = [];

                for ($i =0; $i < count($picture); $i++)
                {
                    $fileList[] = $this->getParameter('pictures_directory') . '/' .$picture[$i]->getFilename();
                }

                $fs = new Filesystem();
                $fs->remove($fileList);

                $query = $em->createQuery('DELETE FROM AppBundle:Picture p WHERE p.markerId = '. $id);
                $query->getResult();

                $query = $em->createQuery('DELETE FROM AppBundle:Comments c WHERE c.markerId = '. $id);
                $query->getResult();


                $em->remove($mrk);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('map', array('user_id' => $this->getUser()->getId())));
        }

        return $this->render('AppBundle:Data:marker_edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }



}