<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index()
    {
        $products = $this->getUser()->getCart()->getProducts();
        return $this->render('cart/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/cart/product/{value}/removeFromCart", name="remove_from_cart")
     * @param $value
     * @return Response
     */
    public function removeFromCart($value)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($value);

        $user->getCart()->removeProduct($product);

        $updated_cart = $user->getCart();
        $user->setCart($updated_cart);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('cart');
    }
}
