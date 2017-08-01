<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction(Request $request)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            echo 1 + 1;
            die;
        }

        $aaa = $this->getUser();
        var_dump($aaa->getUsername());
        return new Response(
            '<html><body>Admin</body></html>'
        );
        }
}
