<?php

namespace App\Controller;

use App\Entity\Purchase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class PurchaseController extends AbstractController
{
    /**
     * @Route("/purchases", name="purchases")
     */
    public function index()
    {
        $purchases = $this->getDoctrine()
            ->getRepository(Purchase::class)
            ->findAll();

        return $this->render('purchase/index.html.twig', [
            'purchases' => $purchases,
        ]);
    }

    /**
     * @Route("/purchases/purchase/{id}/remove", name="purchase_remove")
     * @param $id
     * @return RedirectResponse
     */
    public function purchaseRemove($id)
    {
        $purchase = $this->getDoctrine()
            ->getRepository(Purchase::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($purchase);
        $entityManager->flush();

        return $this->redirectToRoute('purchases');
    }
}
