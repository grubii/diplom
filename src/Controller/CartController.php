<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index()
    {
        $cart_id = $this->getUser()->getCart()->getId();
        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->find($cart_id);
        dump($this->getUser());die;
        return $this->render('cart/index.html.twig', [
            'controller_name' => $this(),
        ]);
    }
}
