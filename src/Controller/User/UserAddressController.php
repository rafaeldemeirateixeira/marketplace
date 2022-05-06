<?php

namespace App\Controller\User;

use App\Entity\UserAddress;
use App\Repository\UserAddressRepository;
use App\Request\User\UserAddressRequest;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserAddressController extends AbstractController
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
     * @Route("/api/user/addresses", name="api_user_addresses", methods={"POST"})
     * @return JsonResponse
     */
    public function store(UserAddressRequest $request): JsonResponse
    {
        Type::addType('geometry', 'App\Types\Geo\GeometryType');
        $this->entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('GEOMETRY', 'geometry');

        try {
            $data = $request->validated();
            $userAddress = (new UserAddress())
            ->setUserId($this->getUser())
            ->setAddress($data['address'])
            ->setDistrict($data['district'])
            ->setNumber($data['number'] ?? null)
            ->setCity($data['city'])
            ->setState($data['state'])
            ->setZipCode($data['zipCode'])
            ->setCoordinates(new Point($data['latitude'], $data['longitude']))
            ->setCreatedAt();

            /** @var UserAddressRepository */
            $userAddressRepository = $this->entityManager->getRepository(UserAddress::class);
            $userAddressRepository->add($userAddress);

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
