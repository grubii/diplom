<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
       return $this->redirectToRoute("list");
    }

      /**
      * @Route("/welcome", name="welcome")
      */
    public function welcome()
    {
        return $this->render('base.html.twig');
    }
}
