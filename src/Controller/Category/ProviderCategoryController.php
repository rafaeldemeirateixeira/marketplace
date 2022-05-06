<?php

namespace App\Controller\Category;

use App\Entity\ProviderCategory;
use App\Repository\ProviderCategoryRepository;
use App\Request\Category\ProviderCategoryRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProviderCategoryController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/category/providers", name="api_category_providers", methods={"POST"})
     * @return JsonResponse
     */
    public function store(ProviderCategoryRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $providerCategory = (new ProviderCategory())
                ->setName($data['name'])
                ->setCreatedAt();

            /** @var ProviderCategoryRepository */
            $userAddressRepository = $this->entityManager->getRepository(ProviderCategory::class);
            $userAddressRepository->add($providerCategory);

            return $this->json([
                'data' => [],
                'message' => 'success'
            ], 201);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
