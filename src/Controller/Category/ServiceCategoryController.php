<?php

namespace App\Controller\Category;

use App\Entity\ProviderCategory;
use App\Entity\ServiceCategory;
use App\Repository\ServiceCategoryRepository;
use App\Request\Category\ServiceCategoryRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServiceCategoryController extends AbstractController
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
     * @Route("/api/category/services", name="api_category_services", methods={"POST"})
     * @return JsonResponse
     */
    public function store(ServiceCategoryRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $serviceCategory = (new ServiceCategory())
                ->setProviderCategory(
                    $this->entityManager->getReference(ProviderCategory::class, $data['providerCategoryId'])
                )
                ->setName($data['name'])
                ->setCreatedAt();

            /** @var ServiceCategoryRepository */
            $userAddressRepository = $this->entityManager->getRepository(ServiceCategory::class);
            $userAddressRepository->add($serviceCategory);

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
