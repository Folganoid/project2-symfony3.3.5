<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 20.08.17
 * Time: 16:21
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ProfileController
 * @package AppBundle\Controller
 */
class ProfileController extends Controller
{
    /**
     * @Route("/profile/{id}", name="profile")
     */
    public function profileAction(int $id, Request $request, UserPasswordEncoderInterface $encoder)
    {
        if ($this->getUser()->getId() != $id) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw new \Exception('Access denied!');
            }
        }

        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneBy(
            array('id' => $id)
        );

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $form = $this->createForm(ProfileType::class, null,
            [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'field' => ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) ? true : false,
            ]
        );
        $form->handleRequest($request);

        /**
         * edit profile form submit
         */
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $usr = $em->getRepository(User::class)->find($id);

            /**
             * check exist user & email by DB
             */
            if ($data['username'] != $usr->getUsername()) {
                if ($em->getRepository(User::class)->findByUsername($data['username'])) {
                    throw new \Exception('User already exist!');
                };
            };

            if ($data['email'] != $usr->getEmail()) {
                if ($em->getRepository(User::class)->findByEmail($data['email'])) {
                    throw new \Exception('Email already busy!');
                };
            };

            $usr->setUsername($data['username']);
            $usr->setEmail($data['email']);

            if ($data['password']) {
                $usr->setPassword($encoder->encodePassword(new User(), $data['password']));
            }

            if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                $usr->setRole($data['role']);
            }

            $em->persist($usr);
            $em->flush();

            $uri = $this->generateUrl('profile', array('id' => $id));
            return $this->redirect($uri);
        }

        return $this->render('AppBundle:Profile:profile.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}