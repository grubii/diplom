<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Entity\Product;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request)
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $products = $this->getUser()->getCart()->getProducts();
        $errorEmptyCart = '';
        $successMsg = '';

        if (count($products) == 0) {
            $errorEmptyCart = "В корзине пусто, вернитесь в каталог и добавьте товары.";
        }

        $total_price = 0;
        foreach ($products as $product) {
            $total_price += $product->getPrice();
        }

        $order = new Purchase();
        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && count($products) != 0) {
            $order = $form->getData();

            $order->setName(
                $form->get('name')->getData()
            );

            $order->setPhoneNumber(
                $form->get('phone_number')->getData()
            );

            $order->setProducts($products);

            $order->setTotalPrice($total_price);


            $entityManager->persist($order);
            $entityManager->flush();

            $this->getUser()->getCart()->removeAllProducts();
            $entityManager->persist($this->getUser());
            $entityManager->flush();

            $errorEmptyCart = "В корзине пусто, вернитесь в каталог и добавьте товары.";
            $successMsg = 'Заказ успешно подтверждён, в скором времени с вами свяжутся.';
        }

        return $this->render('cart/index.html.twig', [
            'products' => $products,
            'orderForm' => $form->createView(),
            'errorEmptyCart' => $errorEmptyCart,
            'successMsg' => $successMsg,
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
