<?php

namespace App\Controller\User;

use App\Entity\ProviderCategory;
use App\Entity\UserProvider;
use App\Repository\ProviderCategoryRepository;
use App\Repository\UserProviderRepository;
use App\Request\User\UserAddressRequest;
use App\Request\User\UserProviderRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserProviderController extends AbstractController
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
     * @Route("/api/user/providers", name="api_user_providers", methods={"POST"})
     * @return JsonResponse
     */
    public function store(UserProviderRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $userProvider = (new UserProvider())
            ->setProviderCategory(
                $this->entityManager->getReference(ProviderCategory::class, $data['providerCategoryId'])
            )
            ->setUser($this->getUser())
            ->setName($data['name'])
            ->setPhone($data['phone'])
            ->setCreatedAt();

            /** @var UserProviderRepository */
            $userProviderRepository = $this->entityManager->getRepository(UserProvider::class);
            $userProviderRepository->add($userProvider);

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
