<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Interface\User\PasswordAuthenticationInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ForgotController extends AbstractController implements PasswordAuthenticationInterface
{
    /** @var UserPasswordHasherInterface */
    private $userPasswordHasher;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ) {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/forgot", name="api_forgot", methods={"PUT"})
     *
     * @param Request $request
     * @return Response
     */
    public function forgotPassword(Request $request): Response
    {
        $statusCode = Response::HTTP_NO_CONTENT;
        $message = [];
        try {
            /** @var User */
            $user = $this->getUser();
            $encriptedPassword = $this->userPasswordHasher->hashPassword($user, $request->request->get('password'));

            /** @var UserRepository */
            $userRepository = $this->entityManager->getRepository(User::class);
            $userRepository->upgradePassword($user, $encriptedPassword);

            return $this->json($message, $statusCode);
        } catch (AccessDeniedHttpException $e) {
            $statusCode = Response::HTTP_BAD_REQUEST;
            $message = $e->getMessage();
        } catch (\Exception $e) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = $e->getMessage();
        }

        return $this->json([
            'message' => $message,
            'code' => $statusCode
        ], $statusCode);
    }
}
