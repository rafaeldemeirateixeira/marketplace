<?php

namespace App\Controller\Place;

use App\Entity\Service;
use App\Entity\ServiceCategory;
use App\Repository\ServiceRepository;
use App\Request\Place\ServiceRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
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
     * @Route("/api/services", name="api_services", methods={"POST"})
     * @return JsonResponse
     */
    public function store(ServiceRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $service = (new Service())
                ->setServiceCategory(
                    $this->entityManager->getReference(ServiceCategory::class, $data['serviceCategoryId'])
                )
                ->setUser($this->getUser())
                ->setName($data['name'])
                ->setDescription($data['description'])
                ->setAmount($data['amount'])
                ->setCreatedAt();

            /** @var ServiceRepository */
            $serviceRepository = $this->entityManager->getRepository(Service::class);
            $serviceRepository->add($service);

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
