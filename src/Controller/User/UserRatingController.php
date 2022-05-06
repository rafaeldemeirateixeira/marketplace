<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Entity\UserRating;
use App\Repository\UserRatingRepository;
use App\Request\User\UserRatingRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserRatingController extends AbstractController
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
     * @Route("/api/user/ratings", name="api_user_ratings", methods={"POST"})
     * @return JsonResponse
     */
    public function store(UserRatingRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $userRating = (new UserRating())
                ->setUserEvaluator($this->getUser())
                ->setUserRated($this->entityManager->getReference(User::class, $data['userRatedId']))
                ->setRate($data['rate'])
                ->setCreatedAt();

            /** @var UserRatingRepository */
            $scheduleRepository = $this->entityManager->getRepository(UserRating::class);
            $scheduleRepository->add($userRating);

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
