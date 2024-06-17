<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index', methods:['get'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $products = $entityManager
            ->getRepository(Product::class)
            ->findAll();

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'sku' => $product->getSku(),
                'name' => $product->getProductName(),
                'description' => $product->getDescription()
            ];
        }

        return $this->json($data);
    }

    #[Route('/products', name: 'product_create', methods:['post'])]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $products = json_decode($request->getContent(), true);

        $response = [];

        foreach ($products as $productRequest) {
            $currentResponse = ['sku' => $productRequest['sku']];
            $product = new Product();
            $product->setSku($productRequest['sku']);
            $product->setProductName($productRequest['product_name']);
            $product->setDescription($productRequest['description']);

            try {
                $entityManager->persist($product);
                $entityManager->flush();

                $currentResponse['status'] = 'success';
                $currentResponse['error'] = false;

            } catch (\Exception $e) {
                $currentResponse['status'] = 'failed';
                $currentResponse['error'] = $e->getMessage();
            }

            $response[] = $currentResponse;
        }

        return $this->json($response, Response::HTTP_CREATED);
    }

    #[Route('/products', name: 'product_update', methods:['put'])]
    public function update(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $productRepository = $entityManager->getRepository(Product::class);
        $products = json_decode($request->getContent(), true);

        $response = [];

        foreach ($products as $productRequest) {
            $currentResponse = ['sku' => $productRequest['sku']];
            $product = $productRepository->findOneBy(['sku' => $productRequest['sku']]);

            if($product) {
                $product->setProductName($productRequest['product_name']);
                $product->setDescription($productRequest['description']);

                try {
                    $entityManager->persist($product);
                    $entityManager->flush();

                    $currentResponse['status'] = 'success';
                    $currentResponse['error'] = false;

                } catch (\Exception $e) {
                    $currentResponse['status'] = 'failed';
                    $currentResponse['error'] = $e->getMessage();
                }
            } else {
                $currentResponse['status'] = 'failed';
                $currentResponse['error'] = 'Product is not exist!';

            }

            $response[] = $currentResponse;
        }

        return $this->json($response, Response::HTTP_CREATED);
    }

    #[Route('/products/{id}', name: 'product_get', methods:['get'])]
    public function get(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No Product found for id ' . $id, 404);
        }

        $data =  [
            'id' => $product->getId(),
            'name' => $product->getProductName(),
            'description' => $product->getDescription(),
        ];

        return $this->json($data);
    }

    #[Route('/products/{id}', name: 'product_delete', methods:['delete'])]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No project found for id ' . $id, 404);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json('Deleted a project successfully with id ' . $id);
    }
}
