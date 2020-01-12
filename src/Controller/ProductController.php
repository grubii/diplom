<?php

namespace App\Controller;

use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\AddProductType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="list")
     * @param Request $request
     * @return Response
     */
    public function list(Request $request)
    {
        $error = '';
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        $form = $this->createForm(SearchType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData();

            if ($search != '') {
                $products = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->findLike($search['name']);

                if (count($products) == 0) {
                    $error = "Товаров с таким названием не существует.";
                }

                $this->redirectToRoute("list");
            }
        }

        return $this->render('product/list.html.twig', [
            'products' => $products,
            'searchProduct' => $form->createView(),
            'errorCount' => $error,
        ]);
    }

    /**
     * @Route("/product/{value}", name="product_show")
     * @param $value
     * @return Response
     */
    public function showProduct($value)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findOneBySomeField($value);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for name '.$value
            );
        }

        return $this->render('product/product.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/product/{value}/delete", name="product_delete")
     * @param $value
     * @return RedirectResponse
     */
    public function deleteProduct($value)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findOneBySomeField($value);

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('list');
    }

    /**
     * @Route("/product/{value}/edit", name="product_edit")
     * @param $value
     * @param Request $request
     * @return Response
     */
    public function editProduct($value , Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $msg = '';

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findOneBySomeField($value);
        
        $form = $this->createForm(AddProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $msg = 'Товар успешно изменён, вы можете добавить ещё один, или вернутся к списку.';
            if ($form->get('name')->getData()) {
                $product->setName(
                    $form->get('name')->getData()
                );
            }
            
            if ($form->get('price')->getData()) {
                $product->setPrice(
                    $form->get('price')->getData()
                );
            }

            if ($form->get('description')->getData()) {
                $product->setDescription(
                    $form->get('description')->getData()
                );
            }

            if ($form->get('category')->getData()) {
                $product->setCategory(
                    $form->get('category')->getData()
                );
            }

            if ($form->get('img')->getData()) {
                $product->setImg(
                    $form->get('img')->getData()
                );
            }
           

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        $form = $this->createForm(AddProductType::class, $product);

        return $this->render('product/edit.html.twig', [
            'EditProduct' => $form->createView(),
            'SuccessMsg' => $msg,
        ]);
    }

    /**
     * @Route("/products/new", name="product_new")
     * @param Request $request
     * @return Response
     */
    public function addProduct(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $msg = '';

        $product = new Product();
        $form = $this->createForm(AddProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $msg = 'Товар успешно добавлен, вы можете добавить ещё один, или вернутся к списку.';
            if ($form->get('name')->getData()) {
                $product->setName(
                    $form->get('name')->getData()
                );
            }
            
            if ($form->get('price')->getData()) {
                $product->setPrice(
                    $form->get('price')->getData()
                );
            }

            if ($form->get('description')->getData()) {
                $product->setDescription(
                    $form->get('description')->getData()
                );
            }

            if ($form->get('category')->getData()) {
                $product->setCategory(
                    $form->get('category')->getData()
                );
            }

            if ($form->get('img')->getData()) {
                $product->setImg(
                    $form->get('img')->getData()
                );
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
        }

        $form = $this->createForm(AddProductType::class, $product);

        return $this->render('product/new.html.twig', [
            'AddProduct' => $form->createView(),
            'SuccessMsg' => $msg,
        ]);
    }
}
