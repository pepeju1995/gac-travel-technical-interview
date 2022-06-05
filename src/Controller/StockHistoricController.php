<?php

namespace App\Controller;

use App\Repository\StockHistoricRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StockHistoricController extends AbstractController
{
    #[Route('/{user}/{product}/stock/historic', name: 'app_stock_historic')]
    public function index($user, $product, StockHistoricRepository $stockRepository): Response
    {
        return $this->render('stock_historic/index.html.twig', [
            'stock_historic' => $stockRepository->findByUserProduct($user, $product),
        ]);
    }
}
