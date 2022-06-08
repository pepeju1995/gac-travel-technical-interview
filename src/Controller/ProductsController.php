<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Products;
use App\Entity\StockHistoric;
use App\Form\ProductsType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use App\Repository\StockHistoricRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'app_products_index', methods: ['GET'])]
    public function index(Request $request, ProductsRepository $productsRepository): Response
    {
        $error = $request->query->get('error');

        return $this->render('products/index.html.twig', [
            'products' => $productsRepository->findAll(),
            'error' => $error
        ]);
    }

    #[Route('/new', name: 'app_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductsRepository $productsRepository, CategoriesRepository $categoriesRepository, StockHistoricRepository $stockHistoricRepository): Response
    {
        $cats = $categoriesRepository->findAll();
        $categories = array();
        $categories[''] = null;
        foreach($cats as $category){
            $categories[$category->getName()] = $category;
        }
        $stock = new StockHistoric();
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product, ['categories' => $categories]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new DateTimeImmutable());
            $product->setStock(0);
            $stock->setUserId($this->getUser());
            $stock->setProductId($product);
            $stock->setCreatedAt(new DateTimeImmutable());
            $stock->setStock(0);
            $productsRepository->add($product);
            $stockHistoricRepository->add($stock);
            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('products/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_stock', methods: ['POST'])]
    public function stock(Request $request, Products $product, ProductsRepository $productsRepository, StockHistoricRepository $stockHistoricRepository): Response
    {    
        $stockActual = $product->getStock();
        $modifStock = $request->get('nuevoStock'.$product->getId());
        
        if($modifStock < 0){
            if($stockActual + $modifStock < 0){
                return $this->redirectToRoute('app_products_index', [
                    'error' => 'No se pueden eliminar mas stock del existente',
                ]);
            }
        } 
        
        $stock = new StockHistoric();
        $stock->setUserId($this->getUser());
        $stock->setProductId($product);
        $stock->setCreatedAt(new DateTimeImmutable());

        $product->setStock($stockActual + $modifStock);
        $stock->setStock($product->getStock());
        $productsRepository->add($product);
        $stockHistoricRepository->add($stock);
        return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        
    }

    #[Route('/{id}/edit', name: 'app_products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Products $product, ProductsRepository $productsRepository, CategoriesRepository $categoriesRepository): Response
    {
        $cats = $categoriesRepository->findAll();
        $categories = array();
        $categories[''] = null;
        foreach($cats as $category){
            $categories[$category->getName()] = $category;
        }

        $form = $this->createForm(ProductsType::class, $product, ['categories' => $categories]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsRepository->add($product);
            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('products/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }
    
}
