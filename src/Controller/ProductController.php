<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\AddProductType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use Knp\Component\Pager\PaginatorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="list")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function list(Request $request, PaginatorInterface $paginator)
    {
        $error = '';
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();


        $form = $this->createForm(SearchType::class, array('categories' => $categories));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData();

            if ($search['name'] != '') {
                $products = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->findLike($search['name']);

                if (count($products) == 0) {
                    $error = 'Товаров с таким названием не существует.';
                }

                $this->redirectToRoute('list');
            }

            if ($search['category'] !== NULL) {
                $products = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->findByCategory($search['category']);

                if (count($products) == 0) {
                    $error = 'Товаров в этой категории не существует.';
                }

                $this->redirectToRoute("list");
            }

            if ($search['name'] != '' && $search['category'] !== NULL) {
                $products = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->findByNameAndCategory($search['name'], $search['category']);

                if (count($products) == 0) {
                    $error = 'Товаров с таким именем в этой категории не существует.';
                }

                $this->redirectToRoute('list');
            }
        }

        $products = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            6
        );

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
            ->findOneByName($value);

        if (!$product) {
            throw $this->createNotFoundException(
                'Продукт с именем '.$value.' не найден.'
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
            ->findOneByName($value);

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
            ->findOneByName($value);
        
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
        $error_msg = '';


        $product = new Product();
        $form = $this->createForm(AddProductType::class, $product);
        $form->handleRequest($request);

        $nameExist = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findOneByName($form->get('name')->getData()) ? 1 : 0;

        if ($form->isSubmitted() && $form->isValid() && !$nameExist) {
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

        if ($nameExist) {
            $error_msg = 'Товар с таким именем уже существует.';
        }

        $form = $this->createForm(AddProductType::class, $product);

        return $this->render('product/new.html.twig', [
            'AddProduct' => $form->createView(),
            'SuccessMsg' => $msg,
            'ErrorMsg' => $error_msg,
        ]);
    }
}
