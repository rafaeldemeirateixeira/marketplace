<?php

namespace App\Controller\Place;

use App\Entity\Schedule;
use App\Entity\Service;
use App\Repository\ScheduleRepository;
use App\Request\Place\ScheduleRequest;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleController extends AbstractController
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
     * @Route("/api/schedules", name="api_schedules", methods={"POST"})
     * @return JsonResponse
     */
    public function store(ScheduleRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            /** @var Service */
            $service = $this->entityManager->getRepository(Service::class)->find($data['serviceId']);

            $schedule = (new Schedule())
                ->setService($service)
                ->setUser($this->getUser())
                ->setDate(DateTime::createFromFormat('Y-m-s', $data['date']))
                ->setTime(DateTime::createFromFormat('H:i:s', $data['time']))
                ->setAmount($service->getAmount())
                ->setObservation($data['observation'])
                ->setCreatedAt();

            /** @var ScheduleRepository */
            $scheduleRepository = $this->entityManager->getRepository(Schedule::class);
            $scheduleRepository->add($schedule);

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
